import { z } from 'zod'

// Auth schemas
export const loginSchema = z.object({
  username: z.string().min(3, 'Username must be at least 3 characters'),
  password: z.string().min(6, 'Password must be at least 6 characters'),
})

export const registerSchema = z.object({
  username: z.string().min(3, 'Username must be at least 3 characters'),
  email: z.string().email('Invalid email address'),
  password: z.string().min(8, 'Password must be at least 8 characters'),
  ico: z.string().optional(),
})

// Income/Expense schemas
export const incomeExpenseSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  documentNumber: z.string().optional(),
  date: z.string().datetime(),
  type: z.enum(['income', 'expense']),
  amount: z.number().positive(),
  taxType: z.enum(['taxable', 'non-taxable']),
  paymentMethod: z.string(),
  description: z.string().optional(),
})

// Asset schemas
export const assetSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  documentNumber: z.string().optional(),
  dateAcquired: z.string().datetime(),
  dateDisposed: z.string().datetime().optional(),
  initialValue: z.number().positive(),
  type: z.enum(['tangible', 'intangible', 'minor_asset']),
  depreciationGroup: z.number().min(1).max(6),
  depreciationMethod: z.enum(['linear', 'accelerated']),
  taxDeductible: z.boolean().default(true),
  paymentMethod: z.string(),
  description: z.string().optional(),
})

// Demand/Debt schemas
export const demandDebtSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  documentNumber: z.string().optional(),
  company: z.string(),
  type: z.enum(['demand', 'debt']),
  dateCreated: z.string().datetime(),
  dateDue: z.string().datetime(),
  amount: z.number().positive(),
  taxType: z.enum(['taxable', 'non-taxable']),
  paymentMethod: z.string(),
  description: z.string().optional(),
})

export type LoginInput = z.infer<typeof loginSchema>
export type RegisterInput = z.infer<typeof registerSchema>
export type IncomeExpenseInput = z.infer<typeof incomeExpenseSchema>
export type AssetInput = z.infer<typeof assetSchema>
export type DemandDebtInput = z.infer<typeof demandDebtSchema>
