import { client } from './client'

export interface AuditLog {
  id: number
  userId?: number
  user?: {
    id: number
    username: string
  }
  action: 'CREATE' | 'UPDATE' | 'DELETE' | 'LOGIN' | 'EXPORT' | 'IMPORT'
  entityType: string
  entityId?: number
  changes?: {
    before?: any
    after?: any
    changed?: Record<string, any>
  }
  ipAddress?: string
  userAgent?: string
  createdAt: string
}

export interface AuditLogsResponse {
  logs: AuditLog[]
  total: number
  limit: number
  offset: number
}

/**
 * Audit Log API Client
 * Handles fetching audit trails and change history
 */
export const auditAPI = {
  /**
   * Get audit logs for current user
   */
  async getUserLogs(filters?: {
    action?: string
    entityType?: string
    startDate?: Date
    endDate?: Date
    limit?: number
    offset?: number
  }): Promise<AuditLogsResponse> {
    const params = new URLSearchParams()
    if (filters?.action) params.append('action', filters.action)
    if (filters?.entityType) params.append('entityType', filters.entityType)
    if (filters?.startDate) params.append('startDate', filters.startDate.toISOString())
    if (filters?.endDate) params.append('endDate', filters.endDate.toISOString())
    if (filters?.limit) params.append('limit', filters.limit.toString())
    if (filters?.offset) params.append('offset', filters.offset.toString())

    const response = await client.get(`/audit/logs?${params}`)
    return response.data
  },

  /**
   * Get audit logs for a specific entity
   */
  async getEntityLogs(entityType: string, entityId: number): Promise<AuditLog[]> {
    const response = await client.get(`/audit/${entityType}/${entityId}`)
    return response.data
  },

  /**
   * Format action for display
   */
  formatAction(action: string): string {
    const actionMap: Record<string, string> = {
      CREATE: '✨ Created',
      UPDATE: '✏️ Updated',
      DELETE: '🗑️ Deleted',
      LOGIN: '🔓 Login',
      EXPORT: '📤 Export',
      IMPORT: '📥 Import',
    }
    return actionMap[action] || action
  },

  /**
   * Format date for display
   */
  formatDate(date: string): string {
    return new Date(date).toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  },
}
