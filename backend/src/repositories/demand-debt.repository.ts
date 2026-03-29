import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

export class DemandDebtRepository {
  async findById(id: number, userId: number) {
    const record = await prisma.demandDebt.findUnique({
      where: { id },
    })
    if (record && record.userId !== userId) return null
    return record
  }

  async findByUser(userId: number, filters?: { type?: string; isPaid?: boolean }) {
    const where: any = { userId }

    if (filters?.type) {
      where.type = filters.type
    }

    if (filters?.isPaid !== undefined) {
      where.isPaid = filters.isPaid
    }

    return prisma.demandDebt.findMany({
      where,
      orderBy: { dateCreated: 'desc' },
    })
  }

  async create(userId: number, data: {
    documentNumber: string
    name: string
    company: string
    type: 'demand' | 'debt'
    taxType: 'taxable' | 'non-taxable'
    dateCreated: Date
    dateDue: Date
    amountCents: bigint
    paymentMethod: string
    description?: string
  }) {
    return prisma.demandDebt.create({
      data: {
        userId,
        ...data,
      },
    })
  }

  async update(id: number, userId: number, data: any) {
    const record = await this.findById(id, userId)
    if (!record) return null

    return prisma.demandDebt.update({
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

    return prisma.demandDebt.delete({
      where: { id },
    })
  }

  async markAsPaid(id: number, userId: number, paymentDate: Date, paymentMethod: string) {
    const record = await this.findById(id, userId)
    if (!record) return null

    return prisma.demandDebt.update({
      where: { id },
      data: {
        isPaid: true,
        paidDate: paymentDate,
        paymentMethod,
        updatedAt: new Date(),
      },
    })
  }

  async getSummary(userId: number, filters?: { type?: string }) {
    const where: any = { userId }

    if (filters?.type) {
      where.type = filters.type
    }

    const [paid, unpaid] = await Promise.all([
      prisma.demandDebt.aggregate({
        where: { ...where, isPaid: true },
        _sum: { amountCents: true },
      }),
      prisma.demandDebt.aggregate({
        where: { ...where, isPaid: false },
        _sum: { amountCents: true },
      }),
    ])

    return {
      paidAmount: paid._sum.amountCents || 0n,
      unpaidAmount: unpaid._sum.amountCents || 0n,
    }
  }
}

export const demandDebtRepository = new DemandDebtRepository()
