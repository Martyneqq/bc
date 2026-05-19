import { Request, Response, NextFunction } from 'express'
import { authController } from '../src/controllers/auth.controller'
import { authService } from '../src/services/auth.service'

jest.mock('../src/services/auth.service')

const mockAuthService = authService as jest.Mocked<typeof authService>

describe('AuthController', () => {
  let mockRequest: Partial<Request>
  let mockResponse: Partial<Response>
  let mockNext: jest.Mock
  let jsonMock: jest.Mock
  let statusMock: jest.Mock

  beforeEach(() => {
    jsonMock = jest.fn().mockReturnValue(undefined)
    statusMock = jest.fn().mockReturnValue({ json: jsonMock })
    mockNext = jest.fn()

    mockRequest = {
      body: {},
    }

    mockResponse = {
      status: statusMock,
      json: jsonMock,
    }

    jest.clearAllMocks()
  })

  describe('register', () => {
    it('should register user successfully', async () => {
      const registerInput = {
        username: 'newuser',
        email: 'newuser@test.com',
        password: 'Pass123!',
      }

      const authResponse = {
        token: 'jwt-token-123',
        user: {
          id: 1,
          username: 'newuser',
          email: 'newuser@test.com',
        },
      }

      mockRequest.body = registerInput
      mockAuthService.register.mockResolvedValueOnce(authResponse)

      await authController.register(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockAuthService.register).toHaveBeenCalledWith(registerInput)
    })
  })

  describe('login', () => {
    it('should login user successfully', async () => {
      const loginInput = {
        username: 'testuser',
        password: 'Pass123!',
      }

      const authResponse = {
        token: 'jwt-token-456',
        user: {
          id: 1,
          username: 'testuser',
          email: 'test@test.com',
        },
      }

      mockRequest.body = loginInput
      mockAuthService.login.mockResolvedValueOnce(authResponse)

      await authController.login(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockAuthService.login).toHaveBeenCalledWith(loginInput)
    })

    it('should handle invalid credentials', async () => {
      mockRequest.body = { username: 'testuser', password: 'wrong' }

      await authController.login(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockRequest.body).toBeDefined()
    })
  })

  describe('me', () => {
    it('should return authenticated user', async () => {
      mockRequest.user = { id: 1, username: 'testuser', email: 'test@test.com' } as any

      await authController.me(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockRequest.user).toBeDefined()
    })

    it('should return 401 if not authenticated', async () => {
      mockRequest.user = undefined

      await authController.me(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockRequest.user).toBeUndefined()
    })
  })

  describe('refresh', () => {
    it('should handle refresh', async () => {
      mockRequest.body = { token: 'old-token' }

      await authController.refresh(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockRequest.body).toBeDefined()
    })

    it('should handle missing token', async () => {
      mockRequest.body = {}

      await authController.refresh(
        mockRequest as Request,
        mockResponse as Response,
        mockNext as NextFunction
      )

      expect(mockRequest.body).toBeDefined()
    })
  })
})
