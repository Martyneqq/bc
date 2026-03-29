import { PrismaClient } from '@prisma/client'
import bcrypt from 'bcryptjs'

const prisma = new PrismaClient()

export class UserRepository {
  async findById(id: number) {
    return prisma.user.findUnique({
      where: { id },
      select: { id: true, username: true, email: true, ico: true },
    })
  }

  async findByUsername(username: string) {
    return prisma.user.findUnique({
      where: { username },
    })
  }

  async findByEmail(email: string) {
    return prisma.user.findUnique({
      where: { email },
    })
  }

  async create(username: string, email: string, password: string, ico?: string) {
    const hashedPassword = await bcrypt.hash(password, 10)
    return prisma.user.create({
      data: {
        username,
        email,
        password: hashedPassword,
        ico,
      },
      select: { id: true, username: true, email: true, ico: true },
    })
  }

  async verifyPassword(user: any, password: string): Promise<boolean> {
    return bcrypt.compare(password, user.password)
  }

  async updateProfile(id: number, data: { email?: string; ico?: string }) {
    return prisma.user.update({
      where: { id },
      data,
      select: { id: true, username: true, email: true, ico: true },
    })
  }

  async changePassword(id: number, newPassword: string) {
    const hashedPassword = await argon2.hash(newPassword)
    return prisma.user.update({
      where: { id },
      data: { password: hashedPassword },
    })
  }
}

export const userRepository = new UserRepository()
