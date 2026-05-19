import { Router, Router as ExpressRouter } from 'express'
import { demandDebtController } from '../controllers/demand-debt.controller'
import { authMiddleware } from '../middleware/auth.middleware'

const router: ReturnType<typeof ExpressRouter> = Router()

router.use(authMiddleware)

router.get('/', demandDebtController.list)
router.post('/', demandDebtController.create)
router.get('/summary', demandDebtController.summary)
router.get('/:id', demandDebtController.getById)
router.put('/:id', demandDebtController.update)
router.delete('/:id', demandDebtController.delete)
router.post('/:id/mark-paid', demandDebtController.markAsPaid)

export default router
