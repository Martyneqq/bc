import { DemandDebtService } from '../src/services/demand-debt.service'

describe('DemandDebtService', () => {
  let service: DemandDebtService

  beforeEach(() => {
    service = new DemandDebtService()
    jest.clearAllMocks()
  })

  it('should be instantiated', () => {
    expect(service).toBeDefined()
  })

  it('should have getList method', () => {
    expect(typeof service.getList).toBe('function')
  })

  it('should have create method', () => {
    expect(typeof service.create).toBe('function')
  })

  it('should have markAsPaid method', () => {
    expect(typeof service.markAsPaid).toBe('function')
  })

  it('should have getSummary method', () => {
    expect(typeof service.getSummary).toBe('function')
  })
})
