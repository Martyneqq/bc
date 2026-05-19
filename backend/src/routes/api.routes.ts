import { Router } from 'express'
import { exportController } from '../controllers'
import { importController } from '../controllers'
import { journalController } from '../controllers'
import { userController } from '../controllers'
import { auditController } from '../controllers/audit.controller'
import { authMiddleware } from '../middleware/auth.middleware'
import multer from 'multer'

const router = Router()

// Configure multer for file uploads
const upload = multer({
  storage: multer.memoryStorage(),
  limits: { fileSize: 10 * 1024 * 1024 }, // 10MB max
})

// ============================================
// EXPORT ROUTES
// ============================================
router.get('/export', authMiddleware, exportController.exportData)

// ============================================
// IMPORT ROUTES
// ============================================
router.post('/import', authMiddleware, upload.single('file'), importController.importData)

// ============================================
// JOURNAL ROUTES
// ============================================
router.get('/journals/general-ledger', authMiddleware, journalController.getGeneralLedger)
router.get('/journals/cash', authMiddleware, journalController.getCashJournal)
router.get('/journals/bank', authMiddleware, journalController.getBankJournal)
router.get('/journals/income', authMiddleware, journalController.getIncomeJournal)
router.get('/journals/expense', authMiddleware, journalController.getExpenseJournal)
router.get('/journals/demand-debt', authMiddleware, journalController.getDemandDebtJournal)
router.get('/journals/summary', authMiddleware, journalController.getJournalSummary)

// ============================================
// USER ROUTES
// ============================================
router.get('/users/me', authMiddleware, userController.getProfile)
router.put('/users/me', authMiddleware, userController.updateProfile)
router.post('/users/me/password', authMiddleware, userController.changePassword)

// ============================================
// AUDIT LOG ROUTES
// ============================================
router.get('/audit/logs', authMiddleware, (req, res, next) => auditController.getUserLogs(req, res, next))
router.get('/audit/:entityType/:entityId', authMiddleware, (req, res, next) =>
  auditController.getEntityLogs(req, res, next),
)

export default router
