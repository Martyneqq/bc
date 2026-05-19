import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_URL

console.log('Vite env:', import.meta.env)
console.log('API_BASE_URL:', API_BASE_URL)

const axiosInstance = axios.create({
  // In dev mode: no baseURL, use relative paths with Vite proxy
  // In production: use absolute VITE_API_URL
  ...(API_BASE_URL ? { baseURL: API_BASE_URL } : {}),
  headers: {
    'Content-Type': 'application/json',
  },
})

// Add token to requests
axiosInstance.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export default axiosInstance
