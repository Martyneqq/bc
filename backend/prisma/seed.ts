import { PrismaClient } from '@prisma/client'
import argon2 from 'argon2'

const prisma = new PrismaClient()

async function main() {
  console.log('🌱 Starting seed...')

  // Create demo user
  const demoUser = await prisma.user.create({
    data: {
      username: 'demo',
      email: 'demo@example.com',
      password: await argon2.hash('Demo@123456'), // Change in production
      ico: '12345678',
    },
  })

  console.log(`✓ Created user: ${demoUser.username}`)

  // Create fiscal year
  const fiscalYear = await prisma.fiscalYear.create({
    data: {
      userId: demoUser.id,
      year: new Date().getFullYear(),
      status: 'open',
    },
  })

  console.log(`✓ Created fiscal year: ${fiscalYear.year}`)

  // Create sample asset
  const asset = await prisma.asset.create({
    data: {
      userId: demoUser.id,
      documentNumber: 'DOC-001',
      name: 'Sample Computer',
      dateAcquired: new Date('2024-01-01'),
      type: 'tangible',
      initialValueCents: 50000 * 100, // 50,000 CZK
      currentValueCents: 50000 * 100,
      depreciationGroup: 4, // 4 years
      depreciationMethod: 'linear',
      paymentMethod: 'bank_transfer',
      description: 'Demo asset for testing',
    },
  })

  console.log(`✓ Created asset: ${asset.name}`)

  // Create sample income/expense
  const incomeExpense = await prisma.incomeExpense.create({
    data: {
      userId: demoUser.id,
      documentNumber: 'DOC-IE-001',
      name: 'Monthly Revenue',
      date: new Date(),
      type: 'income',
      taxType: 'taxable',
      amountCents: 100000 * 100, // 100,000 CZK
      paymentMethod: 'bank_transfer',
      description: 'Demo income entry',
    },
  })

  console.log(`✓ Created income/expense: ${incomeExpense.name}`)

  console.log('✅ Seed completed successfully!')
}

main()
  .catch((e) => {
    console.error('❌ Seed failed:', e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
