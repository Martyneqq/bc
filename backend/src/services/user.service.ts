import { PrismaClient } from '@prisma/client'
import bcryptjs from 'bcryptjs'
import { ApiError } from '../middleware/error.middleware'
import logger from '../utils/logger'

const prisma = new PrismaClient()

interface UpdateProfileInput {
  email?: string
  ico?: string
}

interface ChangePasswordInput {
  currentPassword: string
  newPassword: string
}

/**
 * User Service - Handles user profile management
 * Follows Single Responsibility Principle
 */
export class UserService {
  /**
   * Get user profile
   */
  async getProfile(userId: number) {
    try {
      const user = await prisma.user.findUnique({
        where: { id: userId },
        select: {
          id: true,
          username: true,
          email: true,
          ico: true,
          createdAt: true,
          updatedAt: true,
        },
      })

      if (!user) {
        throw new ApiError(404, 'User not found')
      }

      return user
    } catch (error) {
      logger.error('Error fetching user profile:', error)
      throw error instanceof ApiError ? error : new ApiError(500, 'Failed to fetch profile')
    }
  }

  /**
   * Update user profile
   */
  async updateProfile(userId: number, input: UpdateProfileInput) {
    try {
      // Validate email if provided
      if (input.email) {
        this.validateEmail(input.email)

        // Check if email is already taken
        const existingUser = await prisma.user.findUnique({
          where: { email: input.email },
        })

        if (existingUser && existingUser.id !== userId) {
          throw new ApiError(409, 'Email already in use')
        }
      }

      // Update user
      const updated = await prisma.user.update({
        where: { id: userId },
        data: {
          ...(input.email && { email: input.email }),
          ...(input.ico !== undefined && { ico: input.ico || null }),
          updatedAt: new Date(),
        },
        select: {
          id: true,
          username: true,
          email: true,
          ico: true,
          updatedAt: true,
        },
      })

      logger.info(`User ${userId} profile updated`)

      return updated
    } catch (error) {
      logger.error('Error updating user profile:', error)
      throw error instanceof ApiError ? error : new ApiError(500, 'Failed to update profile')
    }
  }

  /**
   * Change user password
   */
  async changePassword(userId: number, input: ChangePasswordInput) {
    try {
      // Get user with password (for verification)
      const user = await prisma.user.findUnique({
        where: { id: userId },
        select: { id: true, password: true },
      })

      if (!user) {
        throw new ApiError(404, 'User not found')
      }

      // Verify current password
      const isPasswordValid = await bcryptjs.compare(input.currentPassword, user.password)
      if (!isPasswordValid) {
        throw new ApiError(401, 'Current password is incorrect')
      }

      // Validate new password
      this.validatePassword(input.newPassword)

      // Hash new password
      const hashedPassword = await bcryptjs.hash(input.newPassword, 10)

      // Update password
      await prisma.user.update({
        where: { id: userId },
        data: {
          password: hashedPassword,
          updatedAt: new Date(),
        },
      })

      logger.info(`User ${userId} password changed`)

      return { message: 'Password changed successfully' }
    } catch (error) {
      logger.error('Error changing password:', error)
      throw error instanceof ApiError ? error : new ApiError(500, 'Failed to change password')
    }
  }

  /**
   * Validate email format
   */
  private validateEmail(email: string): void {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email)) {
      throw new ApiError(400, 'Invalid email format')
    }
  }

  /**
   * Validate password strength
   */
  private validatePassword(password: string): void {
    if (password.length < 8) {
      throw new ApiError(400, 'Password must be at least 8 characters')
    }

    if (!/[A-Z]/.test(password)) {
      throw new ApiError(400, 'Password must contain at least one uppercase letter')
    }

    if (!/[a-z]/.test(password)) {
      throw new ApiError(400, 'Password must contain at least one lowercase letter')
    }

    if (!/[0-9]/.test(password)) {
      throw new ApiError(400, 'Password must contain at least one number')
    }
  }
}

export const userService = new UserService()
