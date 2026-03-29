// Currency helper - work with amounts in cents to avoid float precision issues

export const CurrencyHelper = {
  /**
   * Convert CZK to cents (BigInt)
   * @example toCents(100) => 10000n
   */
  toCents: (czk: number): bigint => BigInt(Math.round(czk * 100)),

  /**
   * Convert cents (BigInt) to CZK
   * @example fromCents(10000n) => 100
   */
  fromCents: (cents: bigint | number): number => {
    const c = typeof cents === 'bigint' ? Number(cents) : cents
    return c / 100
  },

  /**
   * Format for display with CZK symbol
   * @example format(10000n) => "100,00 Kč"
   */
  format: (cents: bigint | number): string => {
    const czk = CurrencyHelper.fromCents(cents)
    return new Intl.NumberFormat('cs-CZ', {
      style: 'currency',
      currency: 'CZK',
    }).format(czk)
  },

  /**
   * Add two amounts in cents
   */
  add: (a: bigint, b: bigint): bigint => a + b,

  /**
   * Subtract amounts in cents
   */
  subtract: (a: bigint, b: bigint): bigint => a - b,

  /**
   * Multiply amount in cents by a number
   */
  multiply: (cents: bigint, factor: number): bigint =>
    BigInt(Math.round(Number(cents) * factor)),

  /**
   * Divide amount in cents by a number
   */
  divide: (cents: bigint, divisor: number): bigint =>
    BigInt(Math.round(Number(cents) / divisor)),

  /**
   * Calculate percentage of amount
   */
  percentage: (cents: bigint, percent: number): bigint =>
    BigInt(Math.round(Number(cents) * (percent / 100))),
}

export default CurrencyHelper
