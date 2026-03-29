import express, { Express } from 'express'
import cors from 'cors'
import helmet from 'helmet'
import { env } from './config/env'
import { loggerMiddleware } from './middleware/logger.middleware'
import { errorMiddleware } from './middleware/error.middleware'

import authRoutes from './routes/auth.routes'
import incomeExpenseRoutes from './routes/income-expense.routes'
import assetRoutes from './routes/asset.routes'
import demandDebtRoutes from './routes/demand-debt.routes'
import apiRoutes from './routes/api.routes'

const app: Express = express()

// Global middleware
app.use(helmet())
app.use(cors({ origin: env.cors_origin, credentials: true }))
app.use(express.json())
app.use(express.urlencoded({ extended: true }))
app.use(loggerMiddleware)

// Health check
app.get('/health', (_req, res) => {
  res.json({ status: 'ok' })
})

// API routes
app.use('/api/auth', authRoutes)
app.use('/api/income-expense', incomeExpenseRoutes)
app.use('/api/assets', assetRoutes)
app.use('/api/demands-debts', demandDebtRoutes)
app.use('/api', apiRoutes)

// 404 handler
app.use((_req, res) => {
  res.status(404).json({ success: false, error: 'Route not found' })
})

// Error middleware
app.use(errorMiddleware)

export default app
