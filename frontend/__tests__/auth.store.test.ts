import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '../../src/stores/auth.store'
import * as authAPI from '../../src/api/auth.api'

jest.mock('../../src/api/auth.api')

const mockAuthAPI = authAPI as jest.Mocked<typeof authAPI>

describe('Auth Store (Pinia)', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    jest.clearAllMocks()
    localStorage.clear()
  })

  describe('initial state', () => {
    it('should have correct initial state', () => {
      const store = useAuthStore()

      expect(store.token).toBeNull()
      expect(store.user).toBeNull()
      expect(store.isLoading).toBe(false)
      expect(store.error).toBeNull()
    })

    it('should restore token from localStorage if exists', () => {
      const token = 'saved-token-123'
      localStorage.setItem('auth_token', token)

      const store = useAuthStore()

      expect(store.token).toBe(token)
    })
  })

  describe('isAuthenticated', () => {
    it('should return true if token exists', () => {
      const store = useAuthStore()
      store.token = 'valid-token'

      expect(store.isAuthenticated).toBe(true)
    })

    it('should return false if token is null', () => {
      const store = useAuthStore()

      expect(store.isAuthenticated).toBe(false)
    })
  })

  describe('register action', () => {
    it('should register user successfully', async () => {
      const input = {
        username: 'newuser',
        email: 'new@test.com',
        password: 'Pass123!',
        ico: '',
      }

      const response = {
        token: 'jwt-token-123',
        user: {
          id: 1,
          username: 'newuser',
          email: 'new@test.com',
          ico: undefined,
        },
      }

      mockAuthAPI.authAPI.register.mockResolvedValueOnce(response)

      const store = useAuthStore()
      await store.register(input.username, input.email, input.password, input.ico)

      expect(store.token).toBe(response.token)
      expect(store.user).toEqual(response.user)
      expect(store.error).toBeNull()
      expect(localStorage.getItem('auth_token')).toBe(response.token)
    })

    it('should set error if registration fails', async () => {
      const input = {
        username: 'existinguser',
        email: 'existing@test.com',
        password: 'Pass123!',
        ico: '',
      }

      const error = new Error('Username already exists')
      mockAuthAPI.authAPI.register.mockRejectedValueOnce(error)

      const store = useAuthStore()
      try {
        await store.register(input.username, input.email, input.password, input.ico)
      } catch {
        // Expected
      }

      expect(store.token).toBeNull()
      expect(store.user).toBeNull()
      expect(store.error).toBeDefined()
    })
  })

  describe('login action', () => {
    it('should login user successfully', async () => {
      const input = {
        username: 'testuser',
        password: 'Pass123!',
      }

      const response = {
        token: 'jwt-token-456',
        user: {
          id: 1,
          username: 'testuser',
          email: 'test@test.com',
          ico: undefined,
        },
      }

      mockAuthAPI.authAPI.login.mockResolvedValueOnce(response)

      const store = useAuthStore()
      await store.login(input.username, input.password)

      expect(store.token).toBe(response.token)
      expect(store.user).toEqual(response.user)
      expect(store.error).toBeNull()
      expect(localStorage.getItem('auth_token')).toBe(response.token)
    })

    it('should set error if login fails', async () => {
      const input = {
        username: 'testuser',
        password: 'WrongPass',
      }

      const error = new Error('Invalid credentials')
      mockAuthAPI.authAPI.login.mockRejectedValueOnce(error)

      const store = useAuthStore()
      try {
        await store.login(input.username, input.password)
      } catch {
        // Expected
      }

      expect(store.token).toBeNull()
      expect(store.error).toBeDefined()
    })
  })

  describe('logout action', () => {
    it('should clear auth state on logout', () => {
      const store = useAuthStore()
      store.token = 'valid-token'
      store.user = {
        id: 1,
        username: 'testuser',
        email: 'test@test.com',
        ico: undefined,
      }

      store.logout()

      expect(store.token).toBeNull()
      expect(store.user).toBeNull()
      expect(store.error).toBeNull()
      expect(localStorage.getItem('auth_token')).toBeNull()
    })
  })

  describe('clearError action', () => {
    it('should clear error message', () => {
      const store = useAuthStore()
      store.error = 'Some error occurred'

      store.clearError()

      expect(store.error).toBeNull()
    })
  })
})
