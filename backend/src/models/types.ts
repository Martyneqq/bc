export interface JWTPayload {
  id: number
  username: string
  email: string
}

export interface AuthResponse {
  token: string
  user: {
    id: number
    username: string
    email: string
    ico?: string
  }
}

export interface ApiResponse<T> {
  success: boolean
  data?: T
  error?: string
  message?: string
}
