import axios, { AxiosInstance } from 'axios'
import { authAPI } from '../../src/api/auth.api'

jest.mock('axios')

const mockAxios = axios as jest.Mocked<typeof axios>

describe('Auth API Client', () => {
  beforeEach(() => {
    jest.clearAllMocks()
  })

  describe('register', () => {
    it('should call register endpoint with credentials', async () => {
      const input = {
        username: 'newuser',
        email: 'new@test.com',
        password: 'Pass123!',
      }

      const response = {
        success: true,
        data: {
          token: 'jwt-token-123',
          user: {
            id: 1,
            username: 'newuser',
            email: 'new@test.com',
            ico: undefined,
          },
        },
      }

      mockAxios.post.mockResolvedValueOnce({ data: response })

      const result = await authAPI.register(
        input.username,
        input.email,
        input.password
      )

      expect(result).toEqual(response.data)
      expect(mockAxios.post).toHaveBeenCalledWith(
        '/api/auth/register',
        expect.objectContaining({
          username: 'newuser',
          email: 'new@test.com',
          password: 'Pass123!',
        })
      )
    })

    it('should handle registration error', async () => {
      const error = new Error('Username already exists')
      mockAxios.post.mockRejectedValueOnce(error)

      try {
        await authAPI.register('existinguser', 'new@test.com', 'Pass123!')
        fail('Should have thrown error')
      } catch (e) {
        expect(e).toEqual(error)
      }
    })
  })

  describe('login', () => {
    it('should call login endpoint with credentials', async () => {
      const response = {
        success: true,
        data: {
          token: 'jwt-token-456',
          user: {
            id: 1,
            username: 'testuser',
            email: 'test@test.com',
            ico: undefined,
          },
        },
      }

      mockAxios.post.mockResolvedValueOnce({ data: response })

      const result = await authAPI.login('testuser', 'Pass123!')

      expect(result).toEqual(response.data)
      expect(mockAxios.post).toHaveBeenCalledWith(
        '/api/auth/login',
        expect.objectContaining({
          username: 'testuser',
          password: 'Pass123!',
        })
      )
    })

    it('should handle login error', async () => {
      const error = new Error('Invalid credentials')
      mockAxios.post.mockRejectedValueOnce(error)

      try {
        await authAPI.login('testuser', 'WrongPass')
        fail('Should have thrown error')
      } catch (e) {
        expect(e).toEqual(error)
      }
    })
  })

  describe('me', () => {
    it('should get current user data', async () => {
      const response = {
        success: true,
        data: {
          id: 1,
          username: 'testuser',
          email: 'test@test.com',
          ico: undefined,
        },
      }

      mockAxios.get.mockResolvedValueOnce({ data: response })

      const result = await authAPI.me()

      expect(result).toEqual(response.data)
      expect(mockAxios.get).toHaveBeenCalledWith('/api/auth/me')
    })

    it('should handle me endpoint error', async () => {
      const error = new Error('Unauthorized')
      mockAxios.get.mockRejectedValueOnce(error)

      try {
        await authAPI.me()
        fail('Should have thrown error')
      } catch (e) {
        expect(e).toEqual(error)
      }
    })
  })

  describe('refresh', () => {
    it('should refresh JWT token', async () => {
      const oldToken = 'old-token'
      const response = {
        success: true,
        data: {
          token: 'new-jwt-token',
        },
      }

      mockAxios.post.mockResolvedValueOnce({ data: response })

      const result = await authAPI.refresh(oldToken)

      expect(result).toEqual(response.data)
      expect(mockAxios.post).toHaveBeenCalledWith(
        '/api/auth/refresh',
        expect.objectContaining({
          token: oldToken,
        })
      )
    })
  })
})
