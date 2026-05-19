import jwt from 'jsonwebtoken'
import { env } from '../config/env'
import { userRepository } from '../repositories/user.repository'
import { ApiError } from '../middleware/error.middleware'
import { AuthResponse, JWTPayload } from '../models/types'
import { LoginInput, RegisterInput } from '../models/validation'

export class AuthService {
  async register(input: RegisterInput): Promise<AuthResponse> {
    // Check if user exists
    const existingUser = await userRepository.findByUsername(input.username)
    if (existingUser) {
      throw new ApiError(409, 'Username already exists')
    }

    const existingEmail = await userRepository.findByEmail(input.email)
    if (existingEmail) {
      throw new ApiError(409, 'Email already in use')
    }

    // Create user
    const user = await userRepository.create(
      input.username,
      input.email,
      input.password,
      input.ico
    )

    // Generate token
    const token = this.generateToken(user)

    return {
      token,
      user: {
        id: user.id,
        username: user.username,
        email: user.email,
        ico: user.ico || undefined,
      },
    }
  }

  async login(input: LoginInput): Promise<AuthResponse> {
    const user = await userRepository.findByUsername(input.username)
    if (!user) {
      throw new ApiError(401, 'Invalid credentials')
    }

    const isPasswordValid = await userRepository.verifyPassword(user, input.password)
    if (!isPasswordValid) {
      throw new ApiError(401, 'Invalid credentials')
    }

    const token = this.generateToken({
      id: user.id,
      username: user.username,
      email: user.email,
    })

    return {
      token,
      user: {
        id: user.id,
        username: user.username,
        email: user.email,
        ico: user.ico || undefined,
      },
    }
  }

  verifyToken(token: string): JWTPayload {
    try {
      return jwt.verify(token, env.jwt_secret) as JWTPayload
    } catch {
      throw new ApiError(401, 'Invalid token')
    }
  }

  private generateToken(user: any): string {
    const payload: JWTPayload = {
      id: user.id,
      username: user.username,
      email: user.email,
    }

    return jwt.sign(payload as any, env.jwt_secret as any, {
      expiresIn: env.jwt_expire,
    } as any)
  }
}

export const authService = new AuthService()
