import { Request, Response, NextFunction } from 'express'
import logger from '@/utils/logger'

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
  req: Request,
  res: Response,
  next: NextFunction
) => {
  logger.error('Error:', error)

  if (error instanceof ApiError) {
    return res.status(error.statusCode).json({
      success: false,
      error: error.message,
    })
  }

  if (error instanceof SyntaxError) {
    return res.status(400).json({
      success: false,
      error: 'Invalid JSON',
    })
  }

  res.status(500).json({
    success: false,
    error: 'Internal server error',
  })
}

export const asyncHandler = (fn: Function) => {
  return (req: Request, res: Response, next: NextFunction) => {
    Promise.resolve(fn(req, res, next)).catch(next)
  }
}
