import { incomeExpenseRepository } from '../repositories/income-expense.repository'
import { demandDebtRepository } from '../repositories/demand-debt.repository'
import { ApiError } from '../middleware/error.middleware'
import logger from '../utils/logger'

interface JournalEntry {
  date: string
  documentNumber: string
  name: string
  description?: string
  debit?: number
  credit?: number
  balance: number
  category?: string
}

interface JournalFilter {
  startDate?: Date
  endDate?: Date
  category?: string
  type?: string
}

/**
 * Journal Service - Manages accounting journals and ledgers
 * Provides different views of financial data: general ledger,
 * cash book, bank book, income/expense journal
 */
export class JournalService {
  /**
   * Get General Ledger - Summary of all transactions by category
   */
  async getGeneralLedger(userId: number, filters: JournalFilter): Promise<JournalEntry[]> {
    try {
      const incomeExpenses = await incomeExpenseRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
      })

      const entries: JournalEntry[] = []
      let balance = 0

      // Group by date and category
      const grouped = this.groupByDateAndCategory(incomeExpenses)

      for (const [, items] of grouped) {
        for (const item of items) {
          const amount = Number(item.amountCents) / 100

          if (item.type === 'income') {
            balance += amount
          } else {
            balance -= amount
          }

          entries.push({
            date: new Date(item.date).toISOString().split('T')[0],
            documentNumber: item.documentNumber,
            name: item.name,
            description: item.description,
            debit: item.type === 'expense' ? amount : undefined,
            credit: item.type === 'income' ? amount : undefined,
            balance,
            category: item.type,
          })
        }
      }

      return entries.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
    } catch (error) {
      logger.error('Error fetching general ledger:', error)
      throw new ApiError(500, 'Failed to fetch general ledger')
    }
  }

  /**
   * Get Cash Journal - All cash transactions
   */
  async getCashJournal(userId: number, filters: JournalFilter): Promise<JournalEntry[]> {
    try {
      const incomeExpenses = await incomeExpenseRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
        paymentMethod: 'cash',
      })

      return this.formatJournalEntries(incomeExpenses, filters)
    } catch (error) {
      logger.error('Error fetching cash journal:', error)
      throw new ApiError(500, 'Failed to fetch cash journal')
    }
  }

  /**
   * Get Bank Journal - All bank transfer transactions
   */
  async getBankJournal(userId: number, filters: JournalFilter): Promise<JournalEntry[]> {
    try {
      const incomeExpenses = await incomeExpenseRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
        paymentMethod: 'bank_transfer',
      })

      return this.formatJournalEntries(incomeExpenses, filters)
    } catch (error) {
      logger.error('Error fetching bank journal:', error)
      throw new ApiError(500, 'Failed to fetch bank journal')
    }
  }

  /**
   * Get Income Journal - All income transactions
   */
  async getIncomeJournal(userId: number, filters: JournalFilter): Promise<JournalEntry[]> {
    try {
      const incomeExpenses = await incomeExpenseRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
        type: 'income',
      })

      return this.formatJournalEntries(incomeExpenses, filters)
    } catch (error) {
      logger.error('Error fetching income journal:', error)
      throw new ApiError(500, 'Failed to fetch income journal')
    }
  }

  /**
   * Get Expense Journal - All expense transactions
   */
  async getExpenseJournal(userId: number, filters: JournalFilter): Promise<JournalEntry[]> {
    try {
      const incomeExpenses = await incomeExpenseRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
        type: 'expense',
      })

      return this.formatJournalEntries(incomeExpenses, filters)
    } catch (error) {
      logger.error('Error fetching expense journal:', error)
      throw new ApiError(500, 'Failed to fetch expense journal')
    }
  }

  /**
   * Get Demand/Debt Journal
   */
  async getDemandDebtJournal(userId: number, filters: JournalFilter): Promise<JournalEntry[]> {
    try {
      const demandDebts = await demandDebtRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
      })

      let balance = 0

      const entries = demandDebts
        .sort((a, b) => new Date(a.dateCreated).getTime() - new Date(b.dateCreated).getTime())
        .map(item => {
          const amount = Number(item.amountCents) / 100

          if (item.type === 'demand') {
            balance += amount
          } else {
            balance -= amount
          }

          return {
            date: new Date(item.dateCreated).toISOString().split('T')[0],
            documentNumber: item.documentNumber,
            name: item.name,
            description: item.company,
            debit: item.type === 'debt' && !item.isPaid ? amount : undefined,
            credit: item.type === 'demand' && !item.isPaid ? amount : undefined,
            balance,
            category: item.type,
          }
        })

      return entries
    } catch (error) {
      logger.error('Error fetching demand/debt journal:', error)
      throw new ApiError(500, 'Failed to fetch demand/debt journal')
    }
  }

  /**
   * Get summary statistics for date range
   */
  async getJournalSummary(userId: number, filters: JournalFilter) {
    try {
      const incomeExpenses = await incomeExpenseRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
      })

      const demandDebts = await demandDebtRepository.findByUser(userId, {
        dateFrom: filters.startDate,
        dateTo: filters.endDate,
      })

      const summary = {
        totalIncome: 0,
        totalExpense: 0,
        incomeTaxable: 0,
        incomeNonTaxable: 0,
        expenseTaxable: 0,
        expenseNonTaxable: 0,
        cashTotal: 0,
        bankTotal: 0,
        demandsUnpaid: 0,
        debtsUnpaid: 0,
        demandsTotal: 0,
        debtsTotal: 0,
      }

      incomeExpenses.forEach(item => {
        const amount = Number(item.amountCents) / 100

        if (item.type === 'income') {
          summary.totalIncome += amount
          if (item.taxType === 'taxable') {
            summary.incomeTaxable += amount
          } else {
            summary.incomeNonTaxable += amount
          }
        } else {
          summary.totalExpense += amount
          if (item.taxType === 'taxable') {
            summary.expenseTaxable += amount
          } else {
            summary.expenseNonTaxable += amount
          }
        }

        if (item.paymentMethod === 'cash') {
          summary.cashTotal += item.type === 'income' ? amount : -amount
        } else if (item.paymentMethod === 'bank_transfer') {
          summary.bankTotal += item.type === 'income' ? amount : -amount
        }
      })

      demandDebts.forEach(item => {
        const amount = Number(item.amountCents) / 100

        if (item.type === 'demand') {
          summary.demandsTotal += amount
          if (!item.isPaid) summary.demandsUnpaid += amount
        } else {
          summary.debtsTotal += amount
          if (!item.isPaid) summary.debtsUnpaid += amount
        }
      })

      return summary
    } catch (error) {
      logger.error('Error calculating journal summary:', error)
      throw new ApiError(500, 'Failed to calculate summary')
    }
  }

  /**
   * Helper: Format income/expense items as journal entries
   */
  private formatJournalEntries(items: any[], filters: JournalFilter): JournalEntry[] {
    let balance = 0

    return items
      .sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
      .map(item => {
        const amount = Number(item.amountCents) / 100

        if (item.type === 'income') {
          balance += amount
        } else {
          balance -= amount
        }

        return {
          date: new Date(item.date).toISOString().split('T')[0],
          documentNumber: item.documentNumber,
          name: item.name,
          description: item.description,
          debit: item.type === 'expense' ? amount : undefined,
          credit: item.type === 'income' ? amount : undefined,
          balance,
          category: item.type,
        }
      })
  }

  /**
   * Helper: Group items by date and category
   */
  private groupByDateAndCategory(items: any[]) {
    return items.reduce((acc, item) => {
      const key = `${item.date}|${item.type}`
      if (!acc.has(key)) {
        acc.set(key, [])
      }
      acc.get(key).push(item)
      return acc
    }, new Map())
  }
}

export const journalService = new JournalService()
