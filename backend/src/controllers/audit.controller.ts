import { Request, Response, NextFunction } from 'express'
import { auditService } from '../services/audit.service'
import logger from '../utils/logger'

export class AuditController {
  /**
   * Get audit logs for current user
   */
  async getUserLogs(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { action, entityType, startDate, endDate, limit = 100, offset = 0 } = req.query

      const filters: any = {}
      if (action) filters.action = action
      if (entityType) filters.entityType = entityType
      if (startDate) filters.startDate = new Date(startDate as string)
      if (endDate) filters.endDate = new Date(endDate as string)
      filters.limit = Math.min(parseInt(limit as string) || 100, 500) // Max 500
      filters.offset = Math.max(parseInt(offset as string) || 0, 0)

      const result = await auditService.getUserLogs(userId, filters)
      res.json(result)
    } catch (error) {
      next(error)
    }
  }

  /**
   * Get audit logs for a specific entity
   */
  async getEntityLogs(req: Request, res: Response, next: NextFunction) {
    try {
      const { entityType, entityId } = req.params
      const logs = await auditService.getEntityLogs(entityType, parseInt(entityId))
      res.json(logs)
    } catch (error) {
      next(error)
    }
  }
}

export const auditController = new AuditController()
