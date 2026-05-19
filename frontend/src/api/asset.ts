import client from './client'

export interface Asset {
  id: number
  name: string
  documentNumber?: string
  dateAcquired: string
  dateDisposed?: string | null
  initialValue: number
  type: 'tangible' | 'intangible' | 'minor_asset'
  depreciationGroup: number
  depreciationMethod: 'linear' | 'accelerated'
  taxDeductible: boolean
  paymentMethod: string
  description?: string
  userId: number
  createdAt: string
  updatedAt?: string
}

export interface AssetInput {
  name: string
  documentNumber?: string
  dateAcquired: string
  dateDisposed?: string
  initialValue: number
  type: 'tangible' | 'intangible' | 'minor_asset'
  depreciationGroup: number
  depreciationMethod: 'linear' | 'accelerated'
  taxDeductible: boolean
  paymentMethod: string
  description?: string
}

export const assetAPI = {
  async list(includeDisposed: boolean = false): Promise<Asset[]> {
    const response = await client.get(`/api/assets?includeDisposed=${includeDisposed}`)
    return response.data.data
  },

  async getById(id: number): Promise<Asset> {
    const response = await client.get(`/api/assets/${id}`)
    return response.data.data
  },

  async create(input: AssetInput): Promise<Asset> {
    const response = await client.post('/api/assets', input)
    return response.data.data
  },

  async update(id: number, input: Partial<AssetInput>): Promise<Asset> {
    const response = await client.put(`/api/assets/${id}`, input)
    return response.data.data
  },

  async delete(id: number): Promise<void> {
    await client.delete(`/api/assets/${id}`)
  },
}
