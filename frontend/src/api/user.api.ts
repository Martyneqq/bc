import { client } from './client'

export interface UserProfile {
  id: number
  username: string
  email: string
  ico?: string
  createdAt: string
  updatedAt: string
}

/**
 * User API Client
 * Handles user profile management
 */
export const userAPI = {
  /**
   * Get current user profile
   */
  async getProfile(): Promise<UserProfile> {
    const response = await client.get('/users/me')
    return response.data
  },

  /**
   * Update user profile
   */
  async updateProfile(data: {
    email?: string
    ico?: string
  }): Promise<UserProfile> {
    const response = await client.put('/users/me', data)
    return response.data
  },

  /**
   * Change user password
   */
  async changePassword(data: {
    currentPassword: string
    newPassword: string
  }): Promise<{ message: string }> {
    const response = await client.post('/users/me/password', data)
    return response.data
  },
}
