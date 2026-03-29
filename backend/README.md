# Backend Development

## Available Scripts

### `pnpm dev`
Runs the app in development mode with hot reload at http://localhost:3000

### `pnpm build`
Builds TypeScript to JavaScript

### `pnpm start`
Runs the production build

### `pnpm lint`
Runs ESLint on source files

### `pnpm format`
Formats code with Prettier

### Database Commands

```bash
pnpm prisma migrate dev     # Create and run migrations
pnpm prisma db seed         # Run seed script
pnpm prisma studio         # Open Prisma Studio GUI
pnpm prisma generate       # Generate Prisma client
```

## Project Structure

```
src/
├── app.ts                  # Express app setup
├── main.ts                 # Server entry point
├── config/                 # Configuration (env, etc)
├── controllers/            # Request handlers
├── services/               # Business logic
├── repositories/           # Data access layer
├── routes/                 # API routes
├── middleware/             # Express middleware
├── models/                 # Types, DTOs, validation schemas
└── utils/                  # Helper functions
prisma/
├── schema.prisma           # Database schema
└── seed.ts                 # Database seeding
```

## API Response Format

All API responses follow this format:

```json
{
  "success": true,
  "data": { /* response data */ },
  "error": null,
  "message": "Success"
}
```

Error responses:

```json
{
  "success": false,
  "error": "Error message here"
}
```

## Environment Variables

Create `.env` based on `.env.example`:

```
NODE_ENV=development
PORT=3000
DATABASE_URL=postgresql://user:password@localhost:5432/database
JWT_SECRET=your-secret-key
JWT_EXPIRE=7d
CORS_ORIGIN=http://localhost:5173
LOG_LEVEL=debug
```

## Authentication

- JWT tokens in Authorization header: `Bearer <token>`
- Token expiration: 7 days (configurable)
- Password hashing: Argon2
- Refresh token rotation: Supported

## Database

- **Type:** PostgreSQL 15
- **ORM:** Prisma
- **Migrations:** Automatic with Prisma Migrate

### Schema Highlights

- Users with unique username/email
- Income/Expense records with tagging
- Asset tracking with depreciation
- Demand/Debt management
- Audit logging for compliance
- Document counters per user/year

## Tools & Technology

- **Node.js** - Runtime
- **Express** - Web framework
- **TypeScript** - Type safety
- **Prisma** - ORM & migrations
- **PostgreSQL** - Database
- **Jest/Vitest** - Testing
- **Winston** - Logging
- **Zod** - Validation
- **Argon2** - Password hashing
- **JWT** - Authentication
