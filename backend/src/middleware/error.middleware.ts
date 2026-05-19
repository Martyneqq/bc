import { Request, Response, NextFunction } from 'express'
import logger from '../utils/logger'
import { ZodError } from 'zod'

export class ApiError extends Error {
  constructor(
    public statusCode: number,
    message: string
  ) {
    super(message)
    this.name = 'ApiError'
  }
}

export const errorMiddleware = (
  error: Error | ApiError,
  _req: Request,
  res: Response,
  _next: NextFunction
) => {
  logger.error('Error:', error)

  if (error instanceof ApiError) {
    return res.status(error.statusCode).json({
      success: false,
      error: error.message,
    })
  }

  if (error instanceof ZodError) {
    const messages = error.errors.map((e) => `${e.path.join('.')}: ${e.message}`).join('; ')
    return res.status(400).json({
      success: false,
      error: messages || 'Validation error',
    })
  }

  if (error instanceof SyntaxError) {
    return res.status(400).json({
      success: false,
      error: 'Invalid JSON',
    })
  }

  return res.status(500).json({
    success: false,
    error: 'Internal server error',
  })
}

export const asyncHandler = (fn: Function) => {
  return (req: Request, res: Response, next: NextFunction) => {
    Promise.resolve(fn(req, res, next)).catch(next)
  }
}
