import { Request, Response, NextFunction } from 'express'
import jwt from 'jsonwebtoken'
import { env } from '@/config/env'
import logger from '@/utils/logger'
import { JWTPayload } from '@/models/types'

declare global {
  namespace Express {
    interface Request {
      user?: JWTPayload
    }
  }
}

export const authMiddleware = (req: Request, res: Response, next: NextFunction) => {
  try {
    const token = req.headers.authorization?.split(' ')[1]

    if (!token) {
      return res.status(401).json({ success: false, error: 'No token provided' })
    }

    const decoded = jwt.verify(token, env.jwt_secret) as JWTPayload
    req.user = decoded
    next()
  } catch (error) {
    logger.error('Auth middleware error:', error)
    res.status(401).json({ success: false, error: 'Invalid token' })
  }
}

export const optionalAuth = (req: Request, res: Response, next: NextFunction) => {
  try {
    const token = req.headers.authorization?.split(' ')[1]
    if (token) {
      const decoded = jwt.verify(token, env.jwt_secret) as JWTPayload
      req.user = decoded
    }
  } catch (error) {
    logger.debug('Optional auth: no valid token')
  }
  next()
}
