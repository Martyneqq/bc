import axiosInstance from './client'

export const authAPI = {
  register: (data: { username: string; email: string; password: string; ico?: string }) =>
    axiosInstance.post('/api/auth/register', data),

  login: (data: { username: string; password: string }) =>
    axiosInstance.post('/api/auth/login', data),

  me: () => axiosInstance.get('/api/auth/me'),

  refresh: () => axiosInstance.post('/api/auth/refresh'),
}
