import { demandDebtRepository } from '../repositories/demand-debt.repository'
import { DemandDebtInput } from '../models/validation'
import { ApiError } from '../middleware/error.middleware'
import logger from '../utils/logger'

export class DemandDebtService {
  async getList(userId: number, filters?: { type?: string; isPaid?: boolean }) {
    try {
      return await demandDebtRepository.findByUser(userId, filters)
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
    return record
  }

  async create(userId: number, input: DemandDebtInput) {
    try {
      const amountCents = BigInt(Math.round(input.amount * 100))

      return await demandDebtRepository.create(userId, {
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
    } catch (error) {
      logger.error('Error creating demand/debt:', error)
      throw new ApiError(500, 'Failed to create record')
    }
  }

  async update(userId: number, id: number, input: Partial<DemandDebtInput>) {
    try {
      const data: any = { updatedAt: new Date() }

      if (input.name) data.name = input.name
      if (input.company) data.company = input.company
      if (input.description !== undefined) data.description = input.description

      const result = await demandDebtRepository.update(id, userId, data)
      if (!result) {
        throw new ApiError(404, 'Record not found')
      }
      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error updating demand/debt:', error)
      throw new ApiError(500, 'Failed to update record')
    }
  }

  async delete(userId: number, id: number) {
    try {
      const result = await demandDebtRepository.delete(id, userId)
      if (!result) {
        throw new ApiError(404, 'Record not found')
      }
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
