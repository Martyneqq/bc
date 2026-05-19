import { AuthService } from '../src/services/auth.service'
import { userRepository } from '../src/repositories/user.repository'
import { ApiError } from '../src/middleware/error.middleware'
import jwt from 'jsonwebtoken'

jest.mock('../src/repositories/user.repository')
jest.mock('jsonwebtoken')

const mockUserRepository = userRepository as jest.Mocked<typeof userRepository>
const mockJwt = jwt as jest.Mocked<typeof jwt>

describe('AuthService', () => {
  let authService: AuthService

  beforeEach(() => {
    authService = new AuthService()
    jest.clearAllMocks()
  })

  describe('register', () => {
    it('should register a new user successfully', async () => {
      const input = {
        username: 'newuser',
        email: 'newuser@test.com',
        password: 'Pass123!',
        ico: '12345678',
      }

      const mockUser = {
        id: 1,
        username: 'newuser',
        email: 'newuser@test.com',
        ico: '12345678',
        password: 'hashed',
        createdAt: new Date(),
        updatedAt: new Date(),
      }

      mockUserRepository.findByUsername.mockResolvedValueOnce(null)
      mockUserRepository.findByEmail.mockResolvedValueOnce(null)
      mockUserRepository.create.mockResolvedValueOnce(mockUser)
      ;(mockJwt.sign as jest.Mock).mockReturnValueOnce('mocked-jwt-token')

      const result = await authService.register(input)

      expect(result).toEqual({
        token: 'mocked-jwt-token',
        user: expect.objectContaining({
          id: 1,
          username: 'newuser',
          email: 'newuser@test.com',
        }),
      })

      expect(mockUserRepository.findByUsername).toHaveBeenCalledWith('newuser')
      expect(mockUserRepository.findByEmail).toHaveBeenCalledWith('newuser@test.com')
      expect(mockUserRepository.create).toHaveBeenCalled()
    })

    it('should throw error if username already exists', async () => {
      const input = {
        username: 'existinguser',
        email: 'new@test.com',
        password: 'Pass123!',
        ico: '',
      }

      mockUserRepository.findByUsername.mockResolvedValueOnce({
        id: 1,
        username: 'existinguser',
        email: 'old@test.com',
      } as any)

      await expect(authService.register(input)).rejects.toThrow(
        new ApiError(409, 'Username already exists')
      )
    })

    it('should throw error if email already exists', async () => {
      const input = {
        username: 'newuser',
        email: 'existingemail@test.com',
        password: 'Pass123!',
        ico: '',
      }

      mockUserRepository.findByUsername.mockResolvedValueOnce(null)
      mockUserRepository.findByEmail.mockResolvedValueOnce({
        id: 2,
        username: 'otheruser',
        email: 'existingemail@test.com',
      } as any)

      await expect(authService.register(input)).rejects.toThrow(
        new ApiError(409, 'Email already in use')
      )
    })
  })

  describe('login', () => {
    it('should login user successfully with valid credentials', async () => {
      const input = {
        username: 'testuser',
        password: 'Pass123!',
      }

      const mockUser = {
        id: 1,
        username: 'testuser',
        email: 'test@test.com',
        password: 'hashed-password',
        ico: null,
        createdAt: new Date(),
        updatedAt: new Date(),
      }

      mockUserRepository.findByUsername.mockResolvedValueOnce(mockUser)
      mockUserRepository.verifyPassword.mockResolvedValueOnce(true)
      ;(mockJwt.sign as jest.Mock).mockReturnValueOnce('mocked-jwt-token')

      const result = await authService.login(input)

      expect(result).toEqual({
        token: 'mocked-jwt-token',
        user: expect.objectContaining({
          id: 1,
          username: 'testuser',
          email: 'test@test.com',
        }),
      })

      expect(mockUserRepository.findByUsername).toHaveBeenCalledWith('testuser')
      expect(mockUserRepository.verifyPassword).toHaveBeenCalledWith(mockUser, 'Pass123!')
    })

    it('should throw error if user not found', async () => {
      const input = {
        username: 'nonexistent',
        password: 'Pass123!',
      }

      mockUserRepository.findByUsername.mockResolvedValueOnce(null)

      await expect(authService.login(input)).rejects.toThrow(
        new ApiError(401, 'Invalid credentials')
      )
    })

    it('should throw error if password is invalid', async () => {
      const input = {
        username: 'testuser',
        password: 'WrongPassword!',
      }

      const mockUser = {
        id: 1,
        username: 'testuser',
        email: 'test@test.com',
        password: 'hashed-password',
        ico: null,
        createdAt: new Date(),
        updatedAt: new Date(),
      }

      mockUserRepository.findByUsername.mockResolvedValueOnce(mockUser)
      mockUserRepository.verifyPassword.mockResolvedValueOnce(false)

      await expect(authService.login(input)).rejects.toThrow(
        new ApiError(401, 'Invalid credentials')
      )
    })
  })

  describe('verifyToken', () => {
    it('should verify and return token payload', () => {
      const token = 'valid-jwt-token'
      const payload = {
        id: 1,
        username: 'testuser',
        email: 'test@test.com',
      }

      mockJwt.verify.mockReturnValueOnce(payload as any)

      const result = authService.verifyToken(token)

      expect(result).toEqual(payload)
      expect(mockJwt.verify).toHaveBeenCalledWith(token, expect.any(String))
    })

    it('should throw error if token is invalid', () => {
      const token = 'invalid-token'

      mockJwt.verify.mockImplementationOnce(() => {
        throw new Error('Invalid token')
      })

      expect(() => authService.verifyToken(token)).toThrow(ApiError)
    })
  })
})
