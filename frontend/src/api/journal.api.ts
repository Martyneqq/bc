import { client } from './client'

export interface JournalEntry {
  date: string
  documentNumber: string
  name: string
  description?: string
  debit?: number
  credit?: number
  balance: number
  category?: string
}

export interface JournalSummary {
  totalIncome: number
  totalExpense: number
  incomeTaxable: number
  incomeNonTaxable: number
  expenseTaxable: number
  expenseNonTaxable: number
  cashTotal: number
  bankTotal: number
  demandsUnpaid: number
  debtsUnpaid: number
  demandsTotal: number
  debtsTotal: number
}

/**
 * Journal API Client
 * Handles accounting journals and ledgers
 */
export const journalAPI = {
  /**
   * Get General Ledger entries
   */
  async getGeneralLedger(
    startDate?: Date,
    endDate?: Date,
  ): Promise<JournalEntry[]> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())

    const response = await client.get(`/journals/general-ledger?${params}`)
    return response.data
  },

  /**
   * Get Cash Journal entries
   */
  async getCashJournal(
    startDate?: Date,
    endDate?: Date,
  ): Promise<JournalEntry[]> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())

    const response = await client.get(`/journals/cash?${params}`)
    return response.data
  },

  /**
   * Get Bank Journal entries
   */
  async getBankJournal(
    startDate?: Date,
    endDate?: Date,
  ): Promise<JournalEntry[]> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())

    const response = await client.get(`/journals/bank?${params}`)
    return response.data
  },

  /**
   * Get Income Journal entries
   */
  async getIncomeJournal(
    startDate?: Date,
    endDate?: Date,
    category?: string,
  ): Promise<JournalEntry[]> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())
    if (category) params.append('category', category)

    const response = await client.get(`/journals/income?${params}`)
    return response.data
  },

  /**
   * Get Expense Journal entries
   */
  async getExpenseJournal(
    startDate?: Date,
    endDate?: Date,
    category?: string,
  ): Promise<JournalEntry[]> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())
    if (category) params.append('category', category)

    const response = await client.get(`/journals/expense?${params}`)
    return response.data
  },

  /**
   * Get Demand/Debt Journal entries
   */
  async getDemandDebtJournal(
    startDate?: Date,
    endDate?: Date,
  ): Promise<JournalEntry[]> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())

    const response = await client.get(`/journals/demand-debt?${params}`)
    return response.data
  },

  /**
   * Get Journal Summary statistics
   */
  async getJournalSummary(
    startDate?: Date,
    endDate?: Date,
  ): Promise<JournalSummary> {
    const params = new URLSearchParams()
    if (startDate) params.append('startDate', startDate.toISOString())
    if (endDate) params.append('endDate', endDate.toISOString())

    const response = await client.get(`/journals/summary?${params}`)
    return response.data
  },
}
