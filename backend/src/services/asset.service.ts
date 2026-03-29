import { assetRepository } from '../repositories/asset.repository'
import { AssetInput } from '../models/validation'
import { ApiError } from '../middleware/error.middleware'
import { DeprecationHelper } from '../utils/deprecation'
import { auditService } from './audit.service'
import logger from '../utils/logger'

export class AssetService {
  async getList(userId: number, includeDisposed: boolean = false) {
    try {
      return await assetRepository.findByUser(userId, includeDisposed)
    } catch (error) {
      logger.error('Error fetching assets:', error)
      throw new ApiError(500, 'Failed to fetch assets')
    }
  }

  async getById(userId: number, id: number) {
    const asset = await assetRepository.findById(id, userId)
    if (!asset) {
      throw new ApiError(404, 'Asset not found')
    }
    return asset
  }

  async create(userId: number, input: AssetInput) {
    try {
      const initialValueCents = BigInt(Math.round(input.initialValue * 100))

      const asset = await assetRepository.create(userId, {
        documentNumber: input.documentNumber || `ASS-${Date.now()}`,
        name: input.name,
        dateAcquired: new Date(input.dateAcquired),
        type: input.type as 'tangible' | 'intangible' | 'minor_asset',
        initialValueCents,
        depreciationGroup: input.depreciationGroup,
        depreciationMethod: input.depreciationMethod as 'linear' | 'accelerated',
        taxDeductible: input.taxDeductible ?? true,
        paymentMethod: input.paymentMethod,
        description: input.description,
      })

      // Calculate and save depreciation schedule
      const depData = DeprecationHelper.calculateDepreciation(
        input.initialValue,
        input.depreciationGroup,
        new Date(input.dateAcquired),
        input.depreciationMethod as 'linear' | 'accelerated'
      )

      await DeprecationHelper.saveDepreciations(userId, asset.id, depData)

      // Audit log
      await auditService.log({
        action: 'CREATE',
        entityType: 'Asset',
        entityId: asset.id,
        userId,
        changes: { after: asset },
      })

      return asset
    } catch (error) {
      logger.error('Error creating asset:', error)
      throw new ApiError(500, 'Failed to create asset')
    }
  }

  async update(userId: number, id: number, input: Partial<AssetInput>) {
    try {
      const oldAsset = await assetRepository.findById(id, userId)
      if (!oldAsset) {
        throw new ApiError(404, 'Asset not found')
      }

      const data: any = { updatedAt: new Date() }

      if (input.name) data.name = input.name
      if (input.description !== undefined) data.description = input.description
      if (input.paymentMethod) data.paymentMethod = input.paymentMethod

      const result = await assetRepository.update(id, userId, data)

      // Audit log
      const changes = auditService.trackChanges(oldAsset, result)
      if (Object.keys(changes).length > 0) {
        await auditService.log({
          action: 'UPDATE',
          entityType: 'Asset',
          entityId: id,
          userId,
          changes: { before: oldAsset, after: result, changed: changes },
        })
      }

      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error updating asset:', error)
      throw new ApiError(500, 'Failed to update asset')
    }
  }

  async delete(userId: number, id: number) {
    try {
      const asset = await assetRepository.findById(id, userId)
      if (!asset) {
        throw new ApiError(404, 'Asset not found')
      }

      const result = await assetRepository.delete(id, userId)

      // Audit log
      await auditService.log({
        action: 'DELETE',
        entityType: 'Asset',
        entityId: id,
        userId,
        changes: { before: asset },
      })

      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error deleting asset:', error)
      throw new ApiError(500, 'Failed to delete asset')
    }
  }

  async markAsDisposed(userId: number, id: number, disposalDate: Date) {
    try {
      const result = await assetRepository.markAsDisposed(id, userId, disposalDate)
      if (!result) {
        throw new ApiError(404, 'Asset not found')
      }
      return result
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error marking asset as disposed:', error)
      throw new ApiError(500, 'Failed to update asset')
    }
  }

  async getDepreciationSchedule(userId: number, assetId: number) {
    try {
      const asset = await assetRepository.getWithDepreciation(assetId, userId)
      if (!asset) {
        throw new ApiError(404, 'Asset not found')
      }
      return asset
    } catch (error) {
      if (error instanceof ApiError) throw error
      logger.error('Error fetching depreciation schedule:', error)
      throw new ApiError(500, 'Failed to fetch depreciation')
    }
  }
}

export const assetService = new AssetService()
