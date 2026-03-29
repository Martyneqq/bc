import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

export class AssetRepository {
  async findById(id: number, userId: number) {
    const asset = await prisma.asset.findUnique({
      where: { id },
    })
    if (asset && asset.userId !== userId) return null
    return asset
  }

  async findByUser(userId: number, includeDisposed: boolean = false) {
    const where: any = { userId }

    if (!includeDisposed) {
      where.dateDisposed = null
    }

    return prisma.asset.findMany({
      where,
      orderBy: { dateAcquired: 'desc' },
    })
  }

  async create(userId: number, data: {
    documentNumber: string
    name: string
    dateAcquired: Date
    type: 'tangible' | 'intangible' | 'minor_asset'
    initialValueCents: bigint
    depreciationGroup: number
    depreciationMethod: 'linear' | 'accelerated'
    taxDeductible: boolean
    paymentMethod: string
    description?: string
  }) {
    return prisma.asset.create({
      data: {
        userId,
        ...data,
        currentValueCents: data.initialValueCents,
      },
    })
  }

  async update(id: number, userId: number, data: any) {
    const asset = await this.findById(id, userId)
    if (!asset) return null

    return prisma.asset.update({
      where: { id },
      data: {
        ...data,
        updatedAt: new Date(),
      },
    })
  }

  async delete(id: number, userId: number) {
    const asset = await this.findById(id, userId)
    if (!asset) return null

    return prisma.asset.delete({
      where: { id },
    })
  }

  async markAsDisposed(id: number, userId: number, disposalDate: Date) {
    const asset = await this.findById(id, userId)
    if (!asset) return null

    return prisma.asset.update({
      where: { id },
      data: { dateDisposed: disposalDate },
    })
  }

  async getWithDepreciation(id: number, userId: number) {
    return prisma.asset.findUnique({
      where: { id },
      include: {
        depreciations: {
          orderBy: { fiscalYear: 'asc' },
        },
      },
    }).then((asset) => {
      if (asset && asset.userId !== userId) return null
      return asset
    })
  }
}

export const assetRepository = new AssetRepository()
