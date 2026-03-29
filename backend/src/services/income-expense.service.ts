import { incomeExpenseRepository } from '../repositories/income-expense.repository'
import { IncomeExpenseInput } from '../models/validation'
import { ApiError } from '../middleware/error.middleware'
import { auditService } from './audit.service'
import logger from '../utils/logger'
import { serializeRecord, serializeRecords } from '../utils/serializer'

export class IncomeExpenseService {
  async getList(userId: number, filters?: any) {
    try {
      const records = await incomeExpenseRepository.findByUser(userId, filters)
      return serializeRecords(records)
    } catch (error) {
      logger.error('Error fetching income/expenses:', error)
      throw new ApiError(500, 'Failed to fetch records')
    }
  }

  async getById(userId: number, id: number) {
    const record = await incomeExpenseRepository.findById(id, userId)
    if (!record) {
      throw new ApiError(404, 'Record not found')
    }
    return serializeRecord(record)
  }

  async create(userId: number, input: IncomeExpenseInput) {
    try {
      // Convert amount to cents
      const amountCents = BigInt(Math.round(input.amount * 100))

      const record = await incomeExpenseRepository.create(userId, {
        documentNumber: input.documentNumber || `DOC-${Date.now()}`,
        name: input.name,
        date: new Date(input.date),
        type: input.type as 'income' | 'expense',
        taxType: input.taxType as 'taxable' | 'non-taxable',
        amountCents,
        paymentMethod: input.paymentMethod,
        description: input.description,
      })

      // Audit log
      await auditService.log({
        action: 'CREATE',
        entityType: 'IncomeExpense',
        entityId: record.id,
        userId,
        changes: { after: record },
      })

      return serializeRecord(record)
    } catch (error) {
      logger.error('Error creating income/expense:', error)
      throw new ApiError(500, 'Failed to create record')
    }
  }

  async update(userId: number, id: number, input: Partial<IncomeExpenseInput>) {
    try {
      // Get the old record first for audit
      const oldRecord = await incomeExpenseRepository.findById(id, userId)
      if (!oldRecord) {
        throw new ApiError(404, 'Record not found')
      }

      const data: any = { updatedAt: new Date() }

      if (input.name) data.name = input.name
      if (input.date) data.date = new Date(input.date)
      if (input.type) data.type = input.type
      if (input.taxType) data.taxType = input.taxType
      if (input.amount) data.amountCents = BigInt(Math.round(input.amount * 100))
      if (input.paymentMethod) data.paymentMethod = input.paymentMethod
      if (input.description !== undefined) data.description = input.description

      const result = await incomeExpenseRepository.update(id, userId, data)
      if (!result) {
        throw new ApiError(404, 'Record not found')
      }

      // Audit log with before/after changes
      const changes = auditService.trackChanges(oldRecord, result)
      if (Object.keys(changes).length > 0) {
        await auditService.log({
          action: 'UPDATE',
          entityType: 'IncomeExpense',
          entityId: id,
          userId,
          changes: {
            before: oldRecord,
            after: result,
            changed: changes,
          },
        })
      }

      return serializeRecord(result)
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error updating income/expense:', error)
      throw new ApiError(500, 'Failed to update record')
    }
  }

  async delete(userId: number, id: number) {
    try {
      // Get the record before deletion for audit
      const record = await incomeExpenseRepository.findById(id, userId)
      if (!record) {
        throw new ApiError(404, 'Record not found')
      }

      const result = await incomeExpenseRepository.delete(id, userId)

      // Audit log
      await auditService.log({
        action: 'DELETE',
        entityType: 'IncomeExpense',
        entityId: id,
        userId,
        changes: { before: record },
      })

      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error deleting income/expense:', error)
      throw new ApiError(500, 'Failed to delete record')
    }
  }

  async getSummaryByYear(userId: number, year: number) {
    try {
      return await incomeExpenseRepository.getSummaryByYear(userId, year)
    } catch (error) {
      logger.error('Error fetching year summary:', error)
      throw new ApiError(500, 'Failed to fetch summary')
    }
  }
}

export const incomeExpenseService = new IncomeExpenseService()
