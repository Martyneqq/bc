import { PrismaClient } from '@prisma/client'
import { CurrencyHelper } from '@/utils/currency'

const prisma = new PrismaClient()

export class IncomeExpenseRepository {
  async findById(id: number, userId: number) {
    return prisma.incomeExpense.findUnique({
      where: { id },
    }).then((record) => {
      if (record && record.userId !== userId) return null
      return record
    })
  }

  async findByUser(userId: number, filters?: { year?: number; type?: string; startDate?: Date; endDate?: Date }) {
    const where: any = { userId }

    if (filters?.year) {
      const year = filters.year
      where.date = {
        gte: new Date(`${year}-01-01`),
        lt: new Date(`${year + 1}-01-01`),
      }
    }

    if (filters?.startDate && filters?.endDate) {
      where.date = {
        gte: filters.startDate,
        lte: filters.endDate,
      }
    }

    if (filters?.type) {
      where.type = filters.type
    }

    return prisma.incomeExpense.findMany({
      where,
      orderBy: { date: 'desc' },
    })
  }

  async create(userId: number, data: {
    documentNumber: string
    name: string
    date: Date
    type: 'income' | 'expense'
    taxType: 'taxable' | 'non-taxable'
    amountCents: bigint
    paymentMethod: string
    description?: string
    assetId?: number
  }) {
    return prisma.incomeExpense.create({
      data: {
        userId,
        ...data,
        date: new Date(data.date),
      },
    })
  }

  async update(id: number, userId: number, data: any) {
    // Verify ownership
    const record = await this.findById(id, userId)
    if (!record) return null

    return prisma.incomeExpense.update({
      where: { id },
      data: {
        ...data,
        updatedAt: new Date(),
      },
    })
  }

  async delete(id: number, userId: number) {
    const record = await this.findById(id, userId)
    if (!record) return null

    return prisma.incomeExpense.delete({
      where: { id },
    })
  }

  async getSummaryByYear(userId: number, year: number) {
    const startDate = new Date(`${year}-01-01`)
    const endDate = new Date(`${year + 1}-01-01`)

    const records = await prisma.incomeExpense.findMany({
      where: {
        userId,
        date: { gte: startDate, lt: endDate },
      },
    })

    const summary = {
      incomeTaxable: 0n,
      incomeNonTaxable: 0n,
      expenseTaxable: 0n,
      expenseNonTaxable: 0n,
    }

    for (const record of records) {
      if (record.type === 'income' && record.taxType === 'taxable') {
        summary.incomeTaxable += record.amountCents
      } else if (record.type === 'income' && record.taxType === 'non-taxable') {
        summary.incomeNonTaxable += record.amountCents
      } else if (record.type === 'expense' && record.taxType === 'taxable') {
        summary.expenseTaxable += record.amountCents
      } else if (record.type === 'expense' && record.taxType === 'non-taxable') {
        summary.expenseNonTaxable += record.amountCents
      }
    }

    return summary
  }
}

export const incomeExpenseRepository = new IncomeExpenseRepository()
