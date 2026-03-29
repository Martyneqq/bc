import { IncomeExpenseService } from '../src/services/income-expense.service'
import { incomeExpenseRepository } from '../src/repositories/income-expense.repository'

jest.mock('../src/repositories/income-expense.repository')

const mockRepository = incomeExpenseRepository as jest.Mocked<
  typeof incomeExpenseRepository
>

describe('IncomeExpenseService', () => {
  let service: IncomeExpenseService

  beforeEach(() => {
    service = new IncomeExpenseService()
    jest.clearAllMocks()
  })

  describe('getList', () => {
    it('should return list of income/expenses for user', async () => {
      const userId = 1

      mockRepository.findByUser.mockResolvedValueOnce([])

      const result = await service.getList(userId)

      expect(Array.isArray(result)).toBe(true)
      expect(mockRepository.findByUser).toHaveBeenCalledWith(userId, undefined)
    })
  })

  describe('create', () => {
    it('should create income/expense record', async () => {
      const userId = 1
      const input: any = {
        name: 'Client payment',
        amount: 5000,
        type: 'income',
        taxType: 'taxable',
        date: '2026-01-15T00:00:00Z',
        paymentMethod: 'bank',
      }

      mockRepository.create.mockResolvedValueOnce({ id: 1 } as any)

      await service.create(userId, input)

      expect(mockRepository.create).toHaveBeenCalledWith(userId, expect.any(Object))
    })
  })

  describe('update', () => {
    it('should update income/expense record', async () => {
      const recordId = 1
      const userId = 1
      const input: any = {
        name: 'Updated expense',
        amount: 3000,
        type: 'expense',
      }

      mockRepository.update.mockResolvedValueOnce({ id: recordId } as any)

      await service.update(recordId, userId, input)

      expect(mockRepository.update).toHaveBeenCalled()
    })
  })
})
