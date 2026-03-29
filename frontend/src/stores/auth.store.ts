import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authAPI } from '@/api/auth.api'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<any>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value)

  const register = async (username: string, email: string, password: string, ico?: string) => {
    isLoading.value = true
    error.value = null
    try {
      const response = await authAPI.register({ username, email, password, ico })
      token.value = response.data.data.token
      user.value = response.data.data.user
      localStorage.setItem('token', token.value)
      return true
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Registration failed'
      return false
    } finally {
      isLoading.value = false
    }
  }

  const login = async (username: string, password: string) => {
    isLoading.value = true
    error.value = null
    try {
      const response = await authAPI.login({ username, password })
      token.value = response.data.data.token
      user.value = response.data.data.user
      localStorage.setItem('token', token.value)
      return true
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Login failed'
      return false
    } finally {
      isLoading.value = false
    }
  }

  const logout = () => {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  return {
    token,
    user,
    isLoading,
    error,
    isAuthenticated,
    register,
    login,
    logout,
  }
})
