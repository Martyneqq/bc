import { Router } from 'express'
import { incomeExpenseController } from '@/controllers/income-expense.controller'
import { authMiddleware } from '@/middleware/auth.middleware'

const router = Router()

router.use(authMiddleware)

router.get('/', incomeExpenseController.list)
router.post('/', incomeExpenseController.create)
router.get('/summary', incomeExpenseController.summary)
router.get('/:id', incomeExpenseController.getById)
router.put('/:id', incomeExpenseController.update)
router.delete('/:id', incomeExpenseController.delete)

export default router
