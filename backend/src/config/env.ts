import dotenv from 'dotenv'

dotenv.config()

export const env = {
  node_env: process.env.NODE_ENV || 'development',
  port: parseInt(process.env.PORT || '3000', 10),
  database_url: process.env.DATABASE_URL || '',
  jwt_secret: process.env.JWT_SECRET || 'dev-secret',
  jwt_expire: process.env.JWT_EXPIRE || '7d',
  cors_origin: process.env.CORS_ORIGIN || 'http://localhost:5173',
  log_level: process.env.LOG_LEVEL || 'info',
}

console.log('DEBUG: process.env.CORS_ORIGIN =', process.env.CORS_ORIGIN)
console.log('DEBUG: env.cors_origin =', env.cors_origin)

export const isDevelopment = env.node_env === 'development'
export const isProduction = env.node_env === 'production'
