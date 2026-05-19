import { Request, Response } from 'express'
import { incomeExpenseService } from '../services/income-expense.service'
import { incomeExpenseSchema } from '../models/validation'
import { asyncHandler } from '../middleware/error.middleware'

export const incomeExpenseController = {
  list: asyncHandler(async (req: Request, res: Response) => {
    const filters = {
      year: req.query.year ? parseInt(req.query.year as string) : undefined,
      type: (req.query.type as string) || undefined,
    }
    const data = await incomeExpenseService.getList(req.user!.id, filters)
    res.json({ success: true, data })
  }),

  getById: asyncHandler(async (req: Request, res: Response) => {
    const data = await incomeExpenseService.getById(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, data })
  }),

  create: asyncHandler(async (req: Request, res: Response) => {
    const input = incomeExpenseSchema.parse(req.body)
    const data = await incomeExpenseService.create(req.user!.id, input)
    res.status(201).json({ success: true, data })
  }),

  update: asyncHandler(async (req: Request, res: Response) => {
    const input = incomeExpenseSchema.partial().parse(req.body)
    const data = await incomeExpenseService.update(req.user!.id, parseInt(req.params.id), input)
    res.json({ success: true, data })
  }),

  delete: asyncHandler(async (req: Request, res: Response) => {
    await incomeExpenseService.delete(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, message: 'Record deleted' })
  }),

  summary: asyncHandler(async (req: Request, res: Response) => {
    const year = req.query.year ? parseInt(req.query.year as string) : new Date().getFullYear()
    const data = await incomeExpenseService.getSummaryByYear(req.user!.id, year)
    res.json({ success: true, data })
  }),
}
