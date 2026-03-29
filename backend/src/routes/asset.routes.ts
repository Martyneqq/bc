import { Router } from 'express'
import { assetController } from '../controllers/asset.controller'
import { authMiddleware } from '../middleware/auth.middleware'

const router = Router()

router.use(authMiddleware)

router.get('/', assetController.list)
router.post('/', assetController.create)
router.get('/:id', assetController.getById)
router.put('/:id', assetController.update)
router.delete('/:id', assetController.delete)
router.post('/:id/dispose', assetController.markDisposed)
router.get('/:id/depreciation', assetController.getDepreciation)

export default router
