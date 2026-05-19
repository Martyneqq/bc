import { incomeExpenseRepository } from '../repositories/income-expense.repository'
import { assetRepository } from '../repositories/asset.repository'
import { demandDebtRepository } from '../repositories/demand-debt.repository'
import { ApiError } from '../middleware/error.middleware'
import logger from '../utils/logger'

// For XLS export we use xlsx library
import * as xlsx from 'xlsx'

interface ExportOptions {
  type: 'income-expense' | 'assets' | 'demands-debts' | 'all'
  format: 'xlsx' | 'csv'
  filters?: any
  withCalculations?: boolean
}

interface JournalEntryDTO {
  date: string
  documentNumber: string
  name: string
  description?: string
  amount: number
  balance?: number
  category?: string
}

/**
 * Export Service - Handles exporting data in various formats
 * Uses Strategy pattern for different export formats
 */
export class ExportService {
  /**
   * Export data based on options
   */
  async export(userId: number, options: ExportOptions): Promise<Buffer> {
    try {
      logger.info(`Export initiated by user ${userId}`, { type: options.type, format: options.format })

      const data = await this.collectData(userId, options)

      if (options.format === 'xlsx') {
        return await this.exportToXLSX(data, options)
      } else if (options.format === 'csv') {
        return await this.exportToCSV(data, options)
      }

      throw new ApiError(400, 'Unsupported export format')
    } catch (error) {
      logger.error('Export failed:', error)
      throw error instanceof ApiError ? error : new ApiError(500, 'Export failed')
    }
  }

  /**
   * Collect data from repositories
   */
  private async collectData(
    userId: number,
    options: ExportOptions,
  ): Promise<Record<string, any[]>> {
    const data: Record<string, any[]> = {}

    if (options.type === 'all' || options.type === 'income-expense') {
      data['Income & Expense'] = await incomeExpenseRepository.findByUser(userId, options.filters)
    }

    if (options.type === 'all' || options.type === 'assets') {
      data['Assets'] = await assetRepository.findByUser(userId, options.filters)
    }

    if (options.type === 'all' || options.type === 'demands-debts') {
      data['Demands & Debts'] = await demandDebtRepository.findByUser(userId, options.filters)
    }

    return data
  }

  /**
   * Export data to XLSX format
   */
  private async exportToXLSX(data: Record<string, any[]>, options: ExportOptions): Promise<Buffer> {
    const workbook = xlsx.utils.book_new()

    // Add data sheets
    for (const [sheetName, items] of Object.entries(data)) {
      if (items.length === 0) continue

      const processedItems = this.processItemsForExport(items, sheetName, options)
      const worksheet = xlsx.utils.json_to_sheet(processedItems)

      // Set column widths
      this.setColumnWidths(worksheet, processedItems)

      xlsx.utils.book_append_sheet(workbook, worksheet, sheetName)
    }

    // Add metadata sheet
    this.addMetadataSheet(workbook, options)

    // Generate buffer
    return xlsx.write(workbook, { type: 'buffer' })
  }

  /**
   * Export data to CSV format
   */
  private async exportToCSV(data: Record<string, any[]>, options: ExportOptions): Promise<Buffer> {
    const csvParts: string[] = []

    for (const [sheetName, items] of Object.entries(data)) {
      if (items.length === 0) continue

      csvParts.push(`\n"${sheetName}"\n`)

      const processedItems = this.processItemsForExport(items, sheetName, options)
      const csv = xlsx.utils.sheet_to_csv(xlsx.utils.json_to_sheet(processedItems))
      csvParts.push(csv)
    }

    return Buffer.from(csvParts.join('\n'), 'utf-8')
  }

  /**
   * Process items for export - format data for display
   */
  private processItemsForExport(
    items: any[],
    type: string,
    options: ExportOptions,
  ): Record<string, any>[] {
    return items.map(item => {
      const processed: Record<string, any> = {}

      if (type.includes('Income')) {
        processed['Date'] = new Date(item.date).toLocaleDateString('en-US')
        processed['Document #'] = item.documentNumber
        processed['Name'] = item.name
        processed['Type'] = item.type
        processed['Amount'] = (Number(item.amountCents) / 100).toFixed(2)
        processed['Tax Type'] = item.taxType
        processed['Payment Method'] = item.paymentMethod
        if (item.description) processed['Description'] = item.description
      } else if (type.includes('Asset')) {
        processed['Name'] = item.name
        processed['Type'] = item.type
        processed['Acquired'] = new Date(item.dateAcquired).toLocaleDateString('en-US')
        processed['Initial Value'] = (Number(item.initialValueCents) / 100).toFixed(2)
        processed['Current Value'] = (Number(item.currentValueCents) / 100).toFixed(2)
        processed['Depreciation Group'] = item.depreciationGroup
        processed['Method'] = item.depreciationMethod
        processed['Status'] = item.dateDisposed ? 'Disposed' : 'Active'
        if (item.dateDisposed) {
          processed['Disposed Date'] = new Date(item.dateDisposed).toLocaleDateString('en-US')
        }
      } else if (type.includes('Demand')) {
        processed['Date Created'] = new Date(item.dateCreated).toLocaleDateString('en-US')
        processed['Due Date'] = new Date(item.dateDue).toLocaleDateString('en-US')
        processed['Name'] = item.name
        processed['Company'] = item.company
        processed['Type'] = item.type
        processed['Amount'] = (Number(item.amountCents) / 100).toFixed(2)
        processed['Status'] = item.isPaid ? 'Paid' : 'Unpaid'
        if (item.isPaid) {
          processed['Paid Date'] = new Date(item.paidDate).toLocaleDateString('en-US')
        }
      }

      return processed
    })
  }

  /**
   * Set column widths for better readability
   */
  private setColumnWidths(worksheet: any, items: Record<string, any>[]): void {
    if (items.length === 0) return

    const colWidths = Object.keys(items[0]).map(key => ({
      wch: Math.min(Math.max(key.length, 12), 50),
    }))

    worksheet['!cols'] = colWidths
  }

  /**
   * Add metadata sheet with export info
   */
  private addMetadataSheet(workbook: any, options: ExportOptions): void {
    const metadata = [
      { Field: 'Export Date', Value: new Date().toISOString() },
      { Field: 'Export Type', Value: options.type },
      { Field: 'Format', Value: options.format },
    ]

    const worksheet = xlsx.utils.json_to_sheet(metadata)
    worksheet['!cols'] = [{ wch: 20 }, { wch: 30 }]
    xlsx.utils.book_append_sheet(workbook, worksheet, 'Export Info')
  }
}

export const exportService = new ExportService()
