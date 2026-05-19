import app from './app'
import { env } from './config/env'
import logger from './utils/logger'
import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

const startServer = async () => {
  try {
    // Test database connection
    await prisma.$connect()
    logger.info('✓ Database connected')

    // Start server
    app.listen(env.port, () => {
      logger.info(`✓ Server running on port ${env.port}`)
      logger.info(`✓ Environment: ${env.node_env}`)
    })
  } catch (error) {
    logger.error('Failed to start server:', error)
    await prisma.$disconnect()
    process.exit(1)
  }
}

// Handle graceful shutdown
process.on('SIGTERM', async () => {
  logger.info('SIGTERM received, shutting down gracefully')
  await prisma.$disconnect()
  process.exit(0)
})

startServer()
