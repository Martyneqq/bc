import axios from 'axios'
import { client } from './client'

/**
 * Export/Import API Client
 * Handles data import and export operations
 */
export const exportImportAPI = {
  /**
   * Export data in specified format
   * @param type - Type of data to export (income-expense, assets, demands-debts, all)
   * @param format - Export format (xlsx, csv)
   * @param filters - Optional filters to apply
   */
  async export(
    type: 'income-expense' | 'assets' | 'demands-debts' | 'all' = 'all',
    format: 'xlsx' | 'csv' = 'xlsx',
    filters?: Record<string, any>,
  ): Promise<Blob> {
    const params = new URLSearchParams({
      type,
      format,
      ...(filters && { filters: JSON.stringify(filters) }),
    })

    const response = await client.get(`/export?${params}`, {
      responseType: 'blob',
    })

    return response.data
  },

  /**
   * Import data from file
   * @param file - File to import
   * @param type - Type of import (income-expense, assets, demands-debts)
   * @param dryRun - If true, validate without importing
   */
  async import(
    file: File,
    type: 'income-expense' | 'assets' | 'demands-debts',
    dryRun: boolean = false,
  ): Promise<{ succeeded: number; failed: number; errors: any[] }> {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('type', type)
    formData.append('dryRun', String(dryRun))

    const response = await client.post('/import', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    return response.data
  },

  /**
   * Download export as file
   */
  downloadExport(
    blob: Blob,
    filename: string = `export-${new Date().toISOString().split('T')[0]}.xlsx`,
  ): void {
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = filename
    link.click()
    URL.revokeObjectURL(url)
  },
}
