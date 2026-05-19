import { Router, Router as ExpressRouter } from 'express'
import { authController } from '../controllers/auth.controller'
import { authMiddleware } from '../middleware/auth.middleware'

const router: ReturnType<typeof ExpressRouter> = Router()

router.post('/register', authController.register)
router.post('/login', authController.login)
router.post('/refresh', authController.refresh)
router.get('/me', authMiddleware, authController.me)

export default router
