import { client } from './client'

export interface IncomeExpense {
  id: number
  name: string
  documentNumber?: string
  date: string
  type: 'income' | 'expense'
  amount: number
  taxType: 'taxable' | 'non-taxable'
  paymentMethod: string
  description?: string
  userId: number
  createdAt: string
  updatedAt?: string
}

export interface IncomeExpenseInput {
  name: string
  documentNumber?: string
  date: string
  type: 'income' | 'expense'
  amount: number
  taxType: 'taxable' | 'non-taxable'
  paymentMethod: string
  description?: string
}

export interface IncomeExpenseSummary {
  totalIncome: number
  totalExpense: number
  net: number
  byMonth: Record<string, { income: number; expense: number }>
}

export const incomeExpenseAPI = {
  /**
   * Get list of income/expense records with optional filters
   */
  async list(year?: number, type?: 'income' | 'expense'): Promise<IncomeExpense[]> {
    const params = new URLSearchParams()
    if (year) params.append('year', year.toString())
    if (type) params.append('type', type)
    
    const response = await client.get(`/api/income-expense${params.toString() ? '?' + params : ''}`)
    return response.data.data
  },

  /**
   * Get single income/expense record
   */
  async getById(id: number): Promise<IncomeExpense> {
    const response = await client.get(`/api/income-expense/${id}`)
    return response.data.data
  },

  /**
   * Create new income/expense record
   */
  async create(input: IncomeExpenseInput): Promise<IncomeExpense> {
    const response = await client.post('/api/income-expense', input)
    return response.data.data
  },

  /**
   * Update income/expense record
   */
  async update(id: number, input: Partial<IncomeExpenseInput>): Promise<IncomeExpense> {
    const response = await client.put(`/api/income-expense/${id}`, input)
    return response.data.data
  },

  /**
   * Delete income/expense record
   */
  async delete(id: number): Promise<void> {
    await client.delete(`/api/income-expense/${id}`)
  },

  /**
   * Get yearly summary
   */
  async getSummary(year: number): Promise<IncomeExpenseSummary> {
    const response = await client.get(`/api/income-expense/summary?year=${year}`)
    return response.data.data
  },
}
