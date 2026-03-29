import { PrismaClient } from '@prisma/client'
import logger from './logger'

const prisma = new PrismaClient()

export class DeprecationHelper {
  /**
   * Depreciation groups in Czech accounting (1-6)
   * Group 1: 3 years (machinery)
   * Group 2: 5 years (vehicles)
   * Group 3: 10 years (buildings, equipment)
   * Group 4: 20 years (construction)
   * Group 5: 30 years (real estate)
   * Group 6: 50 years (land improvements)
   */
  static readonly DEPRECIATION_YEARS = [3, 5, 10, 20, 30, 50]

  /**
   * Koeficienty pro rovnoměrný odpis - první rok (first year coefficient)
   */
  static readonly FIRST_YEAR_COEF = [20.0, 11.0, 5.5, 2.15, 1.4, 1.02]

  /**
   * Koeficienty pro rovnoměrný odpis - další roky (subsequent years coefficient)
   */
  static readonly SUBSEQUENT_YEARS_COEF = [40.0, 22.25, 10.5, 5.15, 3.4, 2.02]

  /**
   * Calculate depreciation for an asset
   * Returns all depreciation entries from acquisition date to current date
   */
  static calculateDepreciation(
    initialValue: number,
    depreciationGroup: number,
    dateAcquired: Date,
    method: 'linear' | 'accelerated' = 'linear'
  ) {
    const yearsToDep = this.DEPRECIATION_YEARS[depreciationGroup - 1]
    const acquisitionYear = dateAcquired.getFullYear()
    const currentYear = new Date().getFullYear()
    const yearsDiff = currentYear - acquisitionYear

    const depreciations: Array<{ year: number; amount: number; remainingValue: number }> = []
    let remainingValue = initialValue

    for (let year = 0; year < yearsDiff + 1 && year < yearsToDep; year++) {
      let depAmount = 0

      if (method === 'linear') {
        const coef = year === 0 ? this.FIRST_YEAR_COEF[depreciationGroup - 1] : this.SUBSEQUENT_YEARS_COEF[depreciationGroup - 1]
        depAmount = (initialValue * coef) / 100
      } else {
        // Accelerated depreciation (double declining)
        const rate = (2 / yearsToDep) * 100
        depAmount = (remainingValue * rate) / 100
      }

      remainingValue -= depAmount

      depreciations.push({
        year: acquisitionYear + year,
        amount: Math.round(depAmount),
        remainingValue: Math.round(remainingValue),
      })
    }

    return depreciations
  }

  /**
   * Save calculated depreciations to database
   */
  static async saveDepreciations(
    userId: number,
    assetId: number,
    depreciationData: Array<{ year: number; amount: number; remainingValue: number }>
  ) {
    try {
      for (const data of depreciationData) {
        const isLastYear = depreciationData[depreciationData.length - 1].year === data.year

        // Save for each month in the year (typically end of month)
        const lastMonthOfYear = data.year === new Date().getFullYear() ? new Date().getMonth() + 1 : 12

        for (let month = 1; month <= lastMonthOfYear; month++) {
          const monthlyAmount = Math.round(data.amount / 12)

          await prisma.assetDepreciation.upsert({
            where: {
              assetId_fiscalYear_month: {
                assetId,
                fiscalYear: data.year,
                month,
              },
            },
            create: {
              userId,
              assetId,
              fiscalYear: data.year,
              month,
              depreciationAmountCents: BigInt(monthlyAmount * 100),
              remainingValueCents: BigInt(Math.round(data.remainingValue * 100)),
              isLastYear,
            },
            update: {
              depreciationAmountCents: BigInt(monthlyAmount * 100),
              remainingValueCents: BigInt(Math.round(data.remainingValue * 100)),
              isLastYear,
            },
          })
        }
      }

      logger.info(`✓ Depreciation calculations saved for asset ${assetId}`)
    } catch (error) {
      logger.error('Error saving depreciation:', error)
      throw error
    }
  }
}

export default DeprecationHelper
