import { Request, Response } from 'express'
import { assetService } from '../services/asset.service'
import { assetSchema } from '../models/validation'
import { asyncHandler } from '../middleware/error.middleware'

export const assetController = {
  list: asyncHandler(async (req: Request, res: Response) => {
    const includeDisposed = req.query.includeDisposed === 'true'
    const data = await assetService.getList(req.user!.id, includeDisposed)
    res.json({ success: true, data })
  }),

  getById: asyncHandler(async (req: Request, res: Response) => {
    const data = await assetService.getById(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, data })
  }),

  create: asyncHandler(async (req: Request, res: Response) => {
    const input = assetSchema.parse(req.body)
    const data = await assetService.create(req.user!.id, input)
    res.status(201).json({ success: true, data })
  }),

  update: asyncHandler(async (req: Request, res: Response) => {
    const input = assetSchema.partial().parse(req.body)
    const data = await assetService.update(req.user!.id, parseInt(req.params.id), input)
    res.json({ success: true, data })
  }),

  delete: asyncHandler(async (req: Request, res: Response) => {
    await assetService.delete(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, message: 'Asset deleted' })
  }),

  markDisposed: asyncHandler(async (req: Request, res: Response) => {
    const { disposalDate } = req.body
    const data = await assetService.markAsDisposed(req.user!.id, parseInt(req.params.id), new Date(disposalDate))
    res.json({ success: true, data })
  }),

  getDepreciation: asyncHandler(async (req: Request, res: Response) => {
    const data = await assetService.getDepreciationSchedule(req.user!.id, parseInt(req.params.id))
    res.json({ success: true, data })
  }),
}
