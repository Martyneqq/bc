import { AssetService } from '../src/services/asset.service'

describe('AssetService', () => {
  let service: AssetService

  beforeEach(() => {
    service = new AssetService()
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

  it('should have markAsDisposed method', () => {
    expect(typeof service.markAsDisposed).toBe('function')
  })
})
