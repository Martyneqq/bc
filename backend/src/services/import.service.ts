import { Parser } from 'stream-json'
import { streamArray } from 'stream-json/streamers/StreamArray'
import { incomeExpenseRepository } from '../repositories/income-expense.repository'
import { assetRepository } from '../repositories/asset.repository'
import { demandDebtRepository } from '../repositories/demand-debt.repository'
import { ApiError } from '../middleware/error.middleware'
import logger from '../utils/logger'
import * as xlsx from 'xlsx'
import * as fs from 'fs'

interface ImportOptions {
  type: 'income-expense' | 'assets' | 'demands-debts'
  overwrite?: boolean
  dryRun?: boolean
}

interface ImportReport {
  succeeded: number
  failed: number
  errors: { row: number; error: string }[]
}

/**
 * Import Service - Handles importing data from files
 * Uses Factory pattern for different import formats
 */
export class ImportService {
  /**
   * Import data from file buffer
   */
  async import(userId: number, fileBuffer: Buffer, options: ImportOptions): Promise<ImportReport> {
    try {
      logger.info(`Import initiated by user ${userId}`, options)

      // Detect format and parse
      const data = await this.parseFile(fileBuffer)

      // Validate data
      const validationResult = await this.validateData(data, options.type)
      if (!validationResult.isValid) {
        throw new ApiError(400, `Validation failed: ${validationResult.errors.join('; ')}`)
      }

      // Import data
      if (options.dryRun) {
        return {
          succeeded: data.length,
          failed: 0,
          errors: [],
        }
      }

      return await this.importData(userId, data, options)
    } catch (error) {
      logger.error('Import failed:', error)
      throw error instanceof ApiError ? error : new ApiError(500, 'Import failed')
    }
  }

  /**
   * Parse file - supports XLSX and CSV
   */
  private parseFile(fileBuffer: Buffer): any[] {
    try {
      // Try XLSX first
      const workbook = xlsx.read(fileBuffer, { type: 'buffer' })
      const sheetName = workbook.SheetNames[0]
      const worksheet = workbook.Sheets[sheetName]
      return xlsx.utils.sheet_to_json(worksheet)
    } catch (error) {
      // Try CSV
      const csvText = fileBuffer.toString('utf-8')
      const lines = csvText.split('\n').filter(line => line.trim())
      const headers = lines[0].split(',').map(h => h.trim().replace(/^"|"$/g, ''))

      return lines.slice(1).map(line => {
        const values = line.split(',').map(v => v.trim().replace(/^"|"$/g, ''))
        const row: Record<string, any> = {}
        headers.forEach((header, idx) => {
          row[header] = values[idx]
        })
        return row
      })
    }
  }

  /**
   * Validate imported data
   */
  private async validateData(
    data: any[],
    type: string,
  ): Promise<{ isValid: boolean; errors: string[] }> {
    const errors: string[] = []

    if (!Array.isArray(data) || data.length === 0) {
      return { isValid: false, errors: ['No data found in file'] }
    }

    // Validate required fields based on type
    const requiredFields = this.getRequiredFields(type)
    data.forEach((row, idx) => {
      requiredFields.forEach(field => {
        if (!row[field]) {
          errors.push(`Row ${idx + 1}: Missing required field "${field}"`)
        }
      })
    })

    return {
      isValid: errors.length === 0,
      errors: errors.slice(0, 10), // Return first 10 errors
    }
  }

  /**
   * Get required fields for each import type
   */
  private getRequiredFields(type: string): string[] {
    switch (type) {
      case 'income-expense':
        return ['Date', 'Name', 'Type', 'Amount']
      case 'assets':
        return ['Name', 'Type', 'Acquired', 'Initial Value', 'Depreciation Group']
      case 'demands-debts':
        return ['Date Created', 'Name', 'Company', 'Type', 'Amount']
      default:
        return []
    }
  }

  /**
   * Import validated data to database
   */
  private async importData(
    userId: number,
    data: any[],
    options: ImportOptions,
  ): Promise<ImportReport> {
    const report: ImportReport = {
      succeeded: 0,
      failed: 0,
      errors: [],
    }

    for (let idx = 0; idx < data.length; idx++) {
      try {
        const row = data[idx]

        if (options.type === 'income-expense') {
          await this.importIncomeExpense(userId, row)
        } else if (options.type === 'assets') {
          await this.importAsset(userId, row)
        } else if (options.type === 'demands-debts') {
          await this.importDemandDebt(userId, row)
        }

        report.succeeded++
      } catch (error: any) {
        report.failed++
        report.errors.push({
          row: idx + 1,
          error: error.message || 'Unknown error',
        })
      }
    }

    logger.info(`Import completed for user ${userId}`, report)
    return report
  }

  /**
   * Import income/expense record
   */
  private async importIncomeExpense(userId: number, row: any): Promise<void> {
    const date = this.parseDate(row['Date'])
    const amount = this.parseAmount(row['Amount'])

    if (!date || !amount) {
      throw new Error('Invalid date or amount format')
    }

    await incomeExpenseRepository.create(userId, {
      documentNumber: row['Document #'] || `IMP-${Date.now()}`,
      name: row['Name'],
      date,
      type: (row['Type'] || 'income').toLowerCase(),
      taxType: (row['Tax Type'] || 'taxable').toLowerCase(),
      amountCents: BigInt(amount),
      paymentMethod: row['Payment Method'] || 'bank_transfer',
      description: row['Description'],
    })
  }

  /**
   * Import asset record
   */
  private async importAsset(userId: number, row: any): Promise<void> {
    const dateAcquired = this.parseDate(row['Acquired'])
    const initialValue = this.parseAmount(row['Initial Value'])

    if (!dateAcquired || !initialValue) {
      throw new Error('Invalid date or value format')
    }

    const asset = await assetRepository.create(userId, {
      documentNumber: row['Name'] || `ASS-${Date.now()}`,
      name: row['Name'],
      dateAcquired,
      type: (row['Type'] || 'tangible').toLowerCase(),
      initialValueCents: BigInt(initialValue),
      currentValueCents: BigInt(initialValue),
      depreciationGroup: parseInt(row['Depreciation Group'] || '1', 10),
      depreciationMethod: (row['Method'] || 'linear').toLowerCase(),
      taxDeductible: row['Tax Deductible'] !== 'false',
      paymentMethod: row['Payment Method'] || 'bank_transfer',
      description: row['Description'],
    })

    const disposedDate = row['Disposed Date'] ? this.parseDate(row['Disposed Date']) : null
    if (disposedDate) {
      await assetRepository.update(asset.id, userId, { dateDisposed: disposedDate })
    }
  }

  /**
   * Import demand/debt record
   */
  private async importDemandDebt(userId: number, row: any): Promise<void> {
    const dateCreated = this.parseDate(row['Date Created'])
    const dateDue = this.parseDate(row['Due Date'])
    const amount = this.parseAmount(row['Amount'])

    if (!dateCreated || !dateDue || !amount) {
      throw new Error('Invalid date or amount format')
    }

    await demandDebtRepository.create(userId, {
      documentNumber: `DD-${Date.now()}`,
      name: row['Name'],
      company: row['Company'],
      type: (row['Type'] || 'demand').toLowerCase(),
      taxType: (row['Tax Type'] || 'taxable').toLowerCase(),
      dateCreated,
      dateDue,
      amountCents: BigInt(amount),
      isPaid: row['Status'] === 'Paid',
      paidDate: row['Status'] === 'Paid' && row['Paid Date'] ? this.parseDate(row['Paid Date']) : null,
      description: row['Description'],
    })
  }

  /**
   * Parse date string - handles multiple formats
   */
  private parseDate(dateStr: string): Date | null {
    if (!dateStr) return null

    const date = new Date(dateStr)
    return isNaN(date.getTime()) ? null : date
  }

  /**
   * Parse amount string - handles decimal and thousands separators
   */
  private parseAmount(amountStr: string): number | null {
    if (!amountStr) return null

    // Remove currency symbols and whitespace
    let cleaned = amountStr.replace(/[^0-9,.\-]/g, '')

    // Detect decimal separator
    const lastComma = cleaned.lastIndexOf(',')
    const lastDot = cleaned.lastIndexOf('.')

    let normalized = cleaned
    if (lastDot > lastComma) {
      // Dot is decimal separator
      normalized = cleaned.replace(/,/g, '')
    } else if (lastComma > lastDot) {
      // Comma is decimal separator
      normalized = cleaned.replace(/\./g, '').replace(',', '.')
    }

    const num = parseFloat(normalized)
    return isNaN(num) ? null : Math.round(num * 100)
  }
}

export const importService = new ImportService()
