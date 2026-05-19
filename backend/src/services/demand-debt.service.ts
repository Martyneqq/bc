import { demandDebtRepository } from '../repositories/demand-debt.repository'
import { DemandDebtInput } from '../models/validation'
import { ApiError } from '../middleware/error.middleware'
import { auditService } from './audit.service'
import logger from '../utils/logger'
import { serializeRecord, serializeRecords } from '../utils/serializer'

export class DemandDebtService {
  async getList(userId: number, filters?: { type?: string; isPaid?: boolean }) {
    try {
      const records = await demandDebtRepository.findByUser(userId, filters)
      return serializeRecords(records)
    } catch (error) {
      logger.error('Error fetching demands/debts:', error)
      throw new ApiError(500, 'Failed to fetch records')
    }
  }

  async getById(userId: number, id: number) {
    const record = await demandDebtRepository.findById(id, userId)
    if (!record) {
      throw new ApiError(404, 'Record not found')
    }
    return serializeRecord(record)
  }

  async create(userId: number, input: DemandDebtInput) {
    try {
      const amountCents = BigInt(Math.round(input.amount * 100))

      const record = await demandDebtRepository.create(userId, {
        documentNumber: input.documentNumber || `DD-${Date.now()}`,
        name: input.name,
        company: input.company,
        type: input.type as 'demand' | 'debt',
        taxType: input.taxType as 'taxable' | 'non-taxable',
        dateCreated: new Date(input.dateCreated),
        dateDue: new Date(input.dateDue),
        amountCents,
        paymentMethod: input.paymentMethod,
        description: input.description,
      })

      // Audit log
      await auditService.log({
        action: 'CREATE',
        entityType: 'DemandDebt',
        entityId: record.id,
        userId,
        changes: { after: record },
      })

      return serializeRecord(record)
    } catch (error) {
      logger.error('Error creating demand/debt:', error)
      throw new ApiError(500, 'Failed to create record')
    }
  }

  async update(userId: number, id: number, input: Partial<DemandDebtInput>) {
    try {
      const oldRecord = await demandDebtRepository.findById(id, userId)
      if (!oldRecord) {
        throw new ApiError(404, 'Record not found')
      }

      const data: any = { updatedAt: new Date() }

      if (input.name) data.name = input.name
      if (input.company) data.company = input.company
      if (input.description !== undefined) data.description = input.description

      const result = await demandDebtRepository.update(id, userId, data)
      if (!result) {
        throw new ApiError(404, 'Record not found')
      }

      // Audit log
      const changes = auditService.trackChanges(oldRecord, result)
      if (Object.keys(changes).length > 0) {
        await auditService.log({
          action: 'UPDATE',
          entityType: 'DemandDebt',
          entityId: id,
          userId,
          changes: { before: oldRecord, after: result, changed: changes },
        })
      }

      return serializeRecord(result)
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error updating demand/debt:', error)
      throw new ApiError(500, 'Failed to update record')
    }
  }

  async delete(userId: number, id: number) {
    try {
      const record = await demandDebtRepository.findById(id, userId)
      if (!record) {
        throw new ApiError(404, 'Record not found')
      }

      const result = await demandDebtRepository.delete(id, userId)

      // Audit log
      await auditService.log({
        action: 'DELETE',
        entityType: 'DemandDebt',
        entityId: id,
        userId,
        changes: { before: record },
      })

      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error deleting demand/debt:', error)
      throw new ApiError(500, 'Failed to delete record')
    }
  }

  async markAsPaid(userId: number, id: number, paymentDate: Date, paymentMethod: string) {
    try {
      const result = await demandDebtRepository.markAsPaid(id, userId, paymentDate, paymentMethod)
      if (!result) {
        throw new ApiError(404, 'Record not found')
      }
      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error marking as paid:', error)
      throw new ApiError(500, 'Failed to update record')
    }
  }

  async getSummary(userId: number, type?: string) {
    try {
      return await demandDebtRepository.getSummary(userId, { type })
    } catch (error) {
      logger.error('Error fetching summary:', error)
      throw new ApiError(500, 'Failed to fetch summary')
    }
  }
}

export const demandDebtService = new DemandDebtService()
