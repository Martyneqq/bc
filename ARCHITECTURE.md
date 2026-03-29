# Tax Records - Modern Architecture

## Project Structure

```
tax-records/
├── backend/                    # Node.js API Server
│   ├── src/
│   │   ├── controllers/        # Request handlers
│   │   │   ├── auth.controller.ts
│   │   │   ├── income-expense.controller.ts
│   │   │   ├── assets.controller.ts
│   │   │   ├── demands-debts.controller.ts
│   │   │   └── depreciation.controller.ts
│   │   ├── services/           # Business logic
│   │   │   ├── auth.service.ts
│   │   │   ├── income-expense.service.ts
│   │   │   ├── assets.service.ts
│   │   │   ├── depreciation.service.ts
│   │   │   └── demand-debt.service.ts
│   │   ├── repositories/       # Data access
│   │   │   ├── user.repository.ts
│   │   │   ├── income-expense.repository.ts
│   │   │   ├── asset.repository.ts
│   │   │   ├── asset-depreciation.repository.ts
│   │   │   └── demand-debt.repository.ts
│   │   ├── models/             # DTO, types, schemas
│   │   │   ├── types.ts
│   │   │   └── validation.ts
│   │   ├── middleware/         # Middleware stack
│   │   │   ├── auth.middleware.ts
│   │   │   ├── error.middleware.ts
│   │   │   └── logger.middleware.ts
│   │   ├── routes/             # API routes
│   │   │   ├── auth.routes.ts
│   │   │   ├── income-expense.routes.ts
│   │   │   ├── assets.routes.ts
│   │   │   ├── demands-debts.routes.ts
│   │   │   └── depreciation.routes.ts
│   │   ├── utils/              # Helper functions
│   │   │   ├── logger.ts
│   │   │   ├── validators.ts
│   │   │   └── helpers.ts
│   │   ├── config/             # Configuration
│   │   │   └── env.ts
│   │   └── app.ts              # Express app setup
│   ├── prisma/
│   │   ├── schema.prisma       # Database schema
│   │   └── migrations/         # DB migrations
│   ├── tests/                  # Unit & integration tests
│   ├── docker/
│   │   └── Dockerfile
│   ├── package.json
│   ├── tsconfig.json
│   └── .env.example
│
├── frontend/                   # Vue 3 SPA
│   ├── src/
│   │   ├── components/         # Reusable components
│   │   │   ├── layout/
│   │   │   │   ├── Header.vue
│   │   │   │   ├── Sidebar.vue
│   │   │   │   └── Layout.vue
│   │   │   ├── forms/
│   │   │   │   ├── IncomeExpenseForm.vue
│   │   │   │   ├── AssetForm.vue
│   │   │   │   └── DemandDebtForm.vue
│   │   │   ├── tables/
│   │   │   │   ├── DataTable.vue
│   │   │   │   └── PaginationBar.vue
│   │   │   └── modals/
│   │   │       └── ConfirmDialog.vue
│   │   ├── pages/              # Page components
│   │   │   ├── auth/
│   │   │   │   ├── Login.vue
│   │   │   │   └── Register.vue
│   │   │   ├── dashboard/
│   │   │   │   └── Dashboard.vue
│   │   │   ├── income-expense/
│   │   │   │   ├── List.vue
│   │   │   │   └── Detail.vue
│   │   │   ├── assets/
│   │   │   │   ├── List.vue
│   │   │   │   ├── Detail.vue
│   │   │   │   └── Depreciation.vue
│   │   │   ├── demands-debts/
│   │   │   │   └── List.vue
│   │   │   └── profile/
│   │   │       └── Settings.vue
│   │   ├── stores/             # Pinia stores
│   │   │   ├── auth.store.ts
│   │   │   ├── user.store.ts
│   │   │   └── data.store.ts
│   │   ├── composables/        # Reusable logic hooks
│   │   │   ├── useAuth.ts
│   │   │   └── useFetch.ts
│   │   ├── api/                # API client layer
│   │   │   ├── client.ts
│   │   │   ├── auth.api.ts
│   │   │   ├── income-expense.api.ts
│   │   │   ├── assets.api.ts
│   │   │   └── demands-debts.api.ts
│   │   ├── utils/              # Helper functions
│   │   │   ├── format.ts
│   │   │   ├── validation.ts
│   │   │   └── constants.ts
│   │   ├── router/
│   │   │   └── index.ts
│   │   ├── styles/
│   │   │   ├── main.css
│   │   │   └── variables.css
│   │   ├── App.vue
│   │   └── main.ts
│   ├── tests/
│   ├── package.json
│   ├── tsconfig.json
│   ├── vite.config.ts
│   └── .env.example
│
├── docker-compose.yml          # Local development environment
├── .gitignore
├── package.json                # Root workspace
└── pnpm-workspace.yaml         # pnpm workspaces config
```

## Database Schema (PostgreSQL)

### Tables
- **users** - User accounts with auth info
- **income_expense** - Income and expense records
- **assets** - Fixed and minor assets
- **asset_depreciation** - Depreciation calculations
- **demand_debt** - Demands and debts tracking
- **fiscal_year** - Fiscal year settings per user
- **audit_log** - Audit trail for compliance

## API Endpoints

### Authentication
- `POST /api/auth/register` - Create account
- `POST /api/auth/login` - Login
- `POST /api/auth/refresh` - Refresh token
- `POST /api/auth/logout` - Logout

### Income & Expense
- `GET /api/income-expense` - List user records
- `POST /api/income-expense` - Create record
- `PUT /api/income-expense/:id` - Update record
- `DELETE /api/income-expense/:id` - Delete record
- `GET /api/income-expense/summary/:year` - Summary by year

### Assets
- `GET /api/assets` - List assets
- `POST /api/assets` - Add asset
- `PUT /api/assets/:id` - Update asset
- `DELETE /api/assets/:id` - Remove asset
- `GET /api/assets/:id/depreciation` - Depreciation schedule
- `POST /api/assets/:id/depreciation/calculate` - Calculate depreciation

### Demands & Debts
- `GET /api/demands-debts` - List demands/debts
- `POST /api/demands-debts` - Create demand/debt
- `PUT /api/demands-debts/:id` - Update
- `DELETE /api/demands-debts/:id` - Delete
- `PATCH /api/demands-debts/:id/mark-paid` - Mark as paid

### User Settings
- `GET /api/user/profile` - Get profile info
- `PUT /api/user/profile` - Update profile
- `POST /api/user/change-password` - Change password
- `DELETE /api/user/account` - Delete account

## Key Features

### Security
- ✅ JWT-based authentication
- ✅ Password hashing with argon2
- ✅ CORS enabled
- ✅ Rate limiting middleware
- ✅ Input validation with Zod
- ✅ SQL injection protection (Prisma)
- ✅ Audit logging

### Performance
- ✅ Database indexing
- ✅ Query optimization
- ✅ Frontend pagination
- ✅ Caching strategy (Redis-ready)
- ✅ Lazy loading components

### Maintainability
- ✅ Repository pattern
- ✅ Service layer abstraction
- ✅ Type-safe (TypeScript everywhere)
- ✅ Comprehensive error handling
- ✅ Logging and monitoring
- ✅ Unit & integration tests

## Development Workflow

### Local Setup
```bash
# Clone & install
git clone <repo>
cd tax-records
pnpm install

# Start development
docker-compose up -d

# Backend
cd backend && pnpm dev

# Frontend (terminal 2)
cd frontend && pnpm dev
```

### Build & Deploy
```bash
pnpm build
docker build -t tax-records .
```

## Next Steps
1. Setup project structure
2. Create Prisma schema
3. Implement authentication
4. Build API endpoints
5. Develop Vue components
6. Create tests
7. Documentation
