import { Request, Response } from 'express'
import { authService } from '../services/auth.service'
import { loginSchema, registerSchema } from '../models/validation'
import { asyncHandler } from '../middleware/error.middleware'

export const authController = {
  register: asyncHandler(async (req: Request, res: Response) => {
    const input = registerSchema.parse(req.body)
    const result = await authService.register(input)
    res.status(201).json({ success: true, data: result })
  }),

  login: asyncHandler(async (req: Request, res: Response) => {
    const input = loginSchema.parse(req.body)
    const result = await authService.login(input)
    res.json({ success: true, data: result })
  }),

  me: asyncHandler(async (req: Request, res: Response) => {
    if (!req.user) {
      return res.status(401).json({ success: false, error: 'Not authenticated' })
    }
    res.json({ success: true, data: req.user })
  }),

  refresh: asyncHandler(async (req: Request, res: Response) => {
    if (!req.user) {
      return res.status(401).json({ success: false, error: 'Not authenticated' })
    }
    const token = req.body.token
    if (!token) {
      return res.status(400).json({ success: false, error: 'Token required' })
    }
    const payload = authService.verifyToken(token)
    res.json({ success: true, data: { token } })
  }),
}
