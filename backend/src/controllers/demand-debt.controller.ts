import { Request, Response } from 'express'
import { demandDebtService } from '../services/demand-debt.service'
import { demandDebtSchema } from '../models/validation'
import { asyncHandler } from '../middleware/error.middleware'

export const demandDebtController = {
  list: asyncHandler(async (req: Request, res: Response) => {
    const filters = {
      type: (req.query.type as string) || undefined,
      isPaid: req.query.isPaid ? req.query.isPaid === 'true' : undefined,
    }
    const data = await demandDebtService.getList(req.user!.id, filters)
    res.json({ success: true, data })
  }),

  getById: asyncHandler(async (req: Request, res: Response) => {
    const data = await demandDebtService.getById(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, data })
  }),

  create: asyncHandler(async (req: Request, res: Response) => {
    const input = demandDebtSchema.parse(req.body)
    const data = await demandDebtService.create(req.user!.id, input)
    res.status(201).json({ success: true, data })
  }),

  update: asyncHandler(async (req: Request, res: Response) => {
    const input = demandDebtSchema.partial().parse(req.body)
    const data = await demandDebtService.update(req.user!.id, parseInt(req.params.id), input)
    res.json({ success: true, data })
  }),

  delete: asyncHandler(async (req: Request, res: Response) => {
    await demandDebtService.delete(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, message: 'Record deleted' })
  }),

  markAsPaid: asyncHandler(async (req: Request, res: Response) => {
    const { paymentDate, paymentMethod } = req.body
    const data = await demandDebtService.markAsPaid(
      req.user!.id,
      parseInt(req.params.id),
      new Date(paymentDate),
      paymentMethod
    )
    res.json({ success: true, data })
  }),

  summary: asyncHandler(async (req: Request, res: Response) => {
    const type = (req.query.type as string) || undefined
    const data = await demandDebtService.getSummary(req.user!.id, type)
    res.json({ success: true, data })
  }),
}
