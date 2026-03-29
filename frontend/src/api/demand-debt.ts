import client from './client'

export interface DemandDebt {
  id: number
  name: string
  documentNumber?: string
  company: string
  type: 'demand' | 'debt'
  dateCreated: string
  dateDue: string
  amount: number
  taxType: 'taxable' | 'non-taxable'
  paymentMethod: string
  description?: string
  isPaid: boolean
  userId: number
  createdAt: string
  updatedAt?: string
}

export interface DemandDebtInput {
  name: string
  documentNumber?: string
  company: string
  type: 'demand' | 'debt'
  dateCreated: string
  dateDue: string
  amount: number
  taxType: 'taxable' | 'non-taxable'
  paymentMethod: string
  description?: string
}

export const demandDebtAPI = {
  /**
   * Get list of demand/debt records with optional filters
   */
  async list(type?: 'demand' | 'debt', isPaid?: boolean): Promise<DemandDebt[]> {
    const params = new URLSearchParams()
    if (type) params.append('type', type)
    if (isPaid !== undefined) params.append('isPaid', isPaid.toString())

    const response = await client.get(`/api/demands-debts${params.toString() ? '?' + params : ''}`)
    return response.data.data
  },

  /**
   * Get single demand/debt record
   */
  async getById(id: number): Promise<DemandDebt> {
    const response = await client.get(`/api/demands-debts/${id}`)
    return response.data.data
  },

  /**
   * Create new demand/debt record
   */
  async create(input: DemandDebtInput): Promise<DemandDebt> {
    const response = await client.post('/api/demands-debts', input)
    return response.data.data
  },

  /**
   * Update demand/debt record
   */
  async update(id: number, input: Partial<DemandDebtInput>): Promise<DemandDebt> {
    const response = await client.put(`/api/demands-debts/${id}`, input)
    return response.data.data
  },

  /**
   * Delete demand/debt record
   */
  async delete(id: number): Promise<void> {
    await client.delete(`/api/demands-debts/${id}`)
  },

  /**
   * Mark as paid
   */
  async markAsPaid(id: number): Promise<DemandDebt> {
    const response = await client.patch(`/api/demands-debts/${id}/paid`)
    return response.data.data
  },
}
