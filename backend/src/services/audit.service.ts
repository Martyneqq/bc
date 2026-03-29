import { prisma } from '../config/database'
import logger from '../utils/logger'

export interface AuditEntry {
  action: 'CREATE' | 'UPDATE' | 'DELETE' | 'LOGIN' | 'EXPORT' | 'IMPORT'
  entityType: string
  entityId?: number
  userId: number
  changes?: Record<string, any>
  ipAddress?: string
  userAgent?: string
}

export class AuditService {
  /**
   * Log an action to audit trail
   */
  async log(entry: AuditEntry): Promise<void> {
    try {
      await prisma.auditLog.create({
        data: {
          action: entry.action,
          entityType: entry.entityType,
          entityId: entry.entityId,
          userId: entry.userId,
          changes: entry.changes ? JSON.stringify(entry.changes) : null,
          ipAddress: entry.ipAddress,
          userAgent: entry.userAgent,
        },
      })
    } catch (error) {
      logger.error('Audit logging failed', { error, entry })
      // Don't throw - audit logging should not break the main operation
    }
  }

  /**
   * Get audit logs for a user
   */
  async getUserLogs(
    userId: number,
    filters?: {
      action?: string
      entityType?: string
      startDate?: Date
      endDate?: Date
      limit?: number
      offset?: number
    },
  ) {
    const where: any = { userId }

    if (filters?.action) where.action = filters.action
    if (filters?.entityType) where.entityType = filters.entityType

    if (filters?.startDate || filters?.endDate) {
      where.createdAt = {}
      if (filters.startDate) where.createdAt.gte = filters.startDate
      if (filters.endDate) where.createdAt.lte = filters.endDate
    }

    const [logs, total] = await Promise.all([
      prisma.auditLog.findMany({
        where,
        include: {
          user: {
            select: {
              id: true,
              username: true,
            },
          },
        },
        orderBy: { createdAt: 'desc' },
        take: filters?.limit || 100,
        skip: filters?.offset || 0,
      }),
      prisma.auditLog.count({ where }),
    ])

    return {
      logs: logs.map(log => ({
        ...log,
        changes: log.changes ? JSON.parse(log.changes as string) : null,
      })),
      total,
      limit: filters?.limit || 100,
      offset: filters?.offset || 0,
    }
  }

  /**
   * Get audit logs for a specific entity
   */
  async getEntityLogs(entityType: string, entityId: number) {
    const logs = await prisma.auditLog.findMany({
      where: {
        entityType,
        entityId,
      },
      include: {
        user: {
          select: {
            id: true,
            username: true,
          },
        },
      },
      orderBy: { createdAt: 'desc' },
    })

    return logs.map(log => ({
      ...log,
      changes: log.changes ? JSON.parse(log.changes as string) : null,
    }))
  }

  /**
   * Helper to track changes before/after
   */
  trackChanges(before: any, after: any): Record<string, any> {
    const changes: Record<string, any> = {}

    for (const key in after) {
      if (before[key] !== after[key]) {
        changes[key] = {
          before: before[key],
          after: after[key],
        }
      }
    }

    return changes
  }
}

export const auditService = new AuditService()
