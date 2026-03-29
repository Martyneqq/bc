/**
 * Utility to convert BigInt values to numbers for JSON serialization
 */
export function serializeRecord(record: any): any {
  if (!record) return record

  const serialized: any = { ...record }

  // Convert BigInt fields to numbers
  if (serialized.amountCents !== undefined && typeof serialized.amountCents === 'bigint') {
    serialized.amount = Number(serialized.amountCents) / 100
    delete serialized.amountCents
  }

  if (serialized.initialValue !== undefined && typeof serialized.initialValue === 'bigint') {
    serialized.initialValue = Number(serialized.initialValue)
  }

  if (serialized.amount !== undefined && typeof serialized.amount === 'bigint') {
    serialized.amount = Number(serialized.amount)
  }

  return serialized
}

export function serializeRecords(records: any[]): any[] {
  return records.map(serializeRecord)
}
