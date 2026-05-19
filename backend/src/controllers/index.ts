import { Request, Response, NextFunction } from 'express'
import { exportService } from '../services/export.service'
import { importService } from '../services/import.service'
import { journalService } from '../services/journal.service'
import { userService } from '../services/user.service'
import logger from '../utils/logger'

/**
 * Export Controller
 */
export class ExportController {
  async exportData(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { type = 'all', format = 'xlsx', filters } = req.query

      logger.info(`Export request: type=${type}, format=${format}`)

      const buffer = await exportService.export(userId, {
        type: (type as string) || 'all',
        format: (format as string) || 'xlsx',
        filters: filters ? JSON.parse(filters as string) : undefined,
      })

      const fileName = `tax-records-export-${new Date().toISOString().split('T')[0]}.${format}`

      res.setHeader('Content-Type', format === 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv')
      res.setHeader('Content-Disposition', `attachment; filename="${fileName}"`)
      res.send(buffer)
    } catch (error) {
      next(error)
    }
  }
}

/**
 * Import Controller
 */
export class ImportController {
  async importData(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { type } = req.body

      if (!req.file) {
        return res.status(400).json({ error: 'No file uploaded' })
      }

      logger.info(`Import request: type=${type}`)

      const report = await importService.import(userId, req.file.buffer, {
        type: type || 'income-expense',
        dryRun: req.body.dryRun === 'true',
      })

      res.json(report)
    } catch (error) {
      next(error)
    }
  }
}

/**
 * Journal Controller
 */
export class JournalController {
  async getGeneralLedger(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate } = req.query

      const entries = await journalService.getGeneralLedger(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
      })

      res.json(entries)
    } catch (error) {
      next(error)
    }
  }

  async getCashJournal(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate } = req.query

      const entries = await journalService.getCashJournal(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
      })

      res.json(entries)
    } catch (error) {
      next(error)
    }
  }

  async getBankJournal(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate } = req.query

      const entries = await journalService.getBankJournal(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
      })

      res.json(entries)
    } catch (error) {
      next(error)
    }
  }

  async getIncomeJournal(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate, category } = req.query

      const entries = await journalService.getIncomeJournal(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
        category: category as string,
      })

      res.json(entries)
    } catch (error) {
      next(error)
    }
  }

  async getExpenseJournal(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate, category } = req.query

      const entries = await journalService.getExpenseJournal(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
        category: category as string,
      })

      res.json(entries)
    } catch (error) {
      next(error)
    }
  }

  async getDemandDebtJournal(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate } = req.query

      const entries = await journalService.getDemandDebtJournal(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
      })

      res.json(entries)
    } catch (error) {
      next(error)
    }
  }

  async getJournalSummary(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { startDate, endDate } = req.query

      const summary = await journalService.getJournalSummary(userId, {
        startDate: startDate ? new Date(startDate as string) : undefined,
        endDate: endDate ? new Date(endDate as string) : undefined,
      })

      res.json(summary)
    } catch (error) {
      next(error)
    }
  }
}

/**
 * User Controller
 */
export class UserController {
  async getProfile(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const profile = await userService.getProfile(userId)
      res.json(profile)
    } catch (error) {
      next(error)
    }
  }

  async updateProfile(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { email, ico } = req.body

      const updated = await userService.updateProfile(userId, { email, ico })
      res.json(updated)
    } catch (error) {
      next(error)
    }
  }

  async changePassword(req: Request, res: Response, next: NextFunction) {
    try {
      const userId = (req as any).userId
      const { currentPassword, newPassword } = req.body

      const result = await userService.changePassword(userId, {
        currentPassword,
        newPassword,
      })
      res.json(result)
    } catch (error) {
      next(error)
    }
  }
}

export const exportController = new ExportController()
export const importController = new ImportController()
export const journalController = new JournalController()
export const userController = new UserController()
