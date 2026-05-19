# Tax Records - Modern Full-Stack Application

Complete rewrite of tax records system using modern technologies.

## Stack

### Backend
- **Node.js** 20+ with TypeScript
- **Express** - Web framework
- **PostgreSQL** - Database
- **Prisma** - ORM & migrations
- **JWT** - Authentication
- **Argon2** - Password hashing

### Frontend
- **Vue 3** - UI framework
- **TypeScript** - Type safety
- **Pinia** - State management
- **Axios** - HTTP client
- **Vite** - Build tool

### DevOps
- **Docker** & **Docker Compose** - Containerization
- **PostgreSQL 15** - Database container

## Quick Start

### Prerequisites
- Node.js 20+
- Docker & Docker Compose
- pnpm (install with `npm install -g pnpm`)

### Installation

```bash
# Clone repository
git clone <repo>
cd tax-records

# Install dependencies
pnpm install

# Setup environment files
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env
```

### Development

**Option 1: With Docker**
```bash
docker-compose up -d
cd backend && pnpm dev  # Terminal 1
cd frontend && pnpm dev # Terminal 2
```

**Option 2: Local development**
```bash
# Terminal 1 - Backend
cd backend
pnpm dev

# Terminal 2 - Frontend
cd frontend
pnpm dev
```

### Database Setup

```bash
# Run migrations
cd backend
pnpm prisma migrate dev

# Seed demo data
pnpm prisma db seed
```

### Access

- **Frontend:** http://localhost:5173
- **Backend:** http://localhost:3000
- **API Docs:** http://localhost:3000/api/docs

### Demo Credentials
- Username: `demo`
- Email: `demo@example.com`
- Password: `Demo@123456`

## Project Structure

See [ARCHITECTURE.md](./ARCHITECTURE.md) for detailed structure.

## Database

See [DB_ARCHITECTURE.md](./DB_ARCHITECTURE.md) for database design details.

## API Endpoints

### Authentication
- `POST /api/auth/register` - Create account
- `POST /api/auth/login` - Login
- `GET /api/auth/me` - Get current user
- `POST /api/auth/refresh` - Refresh token

### Income & Expense
- `GET /api/income-expense` - List records
- `POST /api/income-expense` - Create record
- `GET /api/income-expense/:id` - Get record
- `PUT /api/income-expense/:id` - Update record
- `DELETE /api/income-expense/:id` - Delete record
- `GET /api/income-expense/summary?year=2024` - Year summary

### Assets
- `GET /api/assets` - List assets
- `POST /api/assets` - Create asset
- `GET /api/assets/:id` - Get asset
- `PUT /api/assets/:id` - Update asset
- `DELETE /api/assets/:id` - Delete asset
- `POST /api/assets/:id/dispose` - Mark as disposed
- `GET /api/assets/:id/depreciation` - Depreciation schedule

### Demands & Debts
- `GET /api/demands-debts` - List
- `POST /api/demands-debts` - Create
- `GET /api/demands-debts/:id` - Get
- `PUT /api/demands-debts/:id` - Update
- `DELETE /api/demands-debts/:id` - Delete
- `POST /api/demands-debts/:id/mark-paid` - Mark as paid
- `GET /api/demands-debts/summary` - Summary

## Development Commands

### Backend
```bash
cd backend
pnpm dev              # Start dev server
pnpm build            # Build for production
pnpm lint             # Run ESLint
pnpm format           # Format code
pnpm prisma studio   # Open Prisma Studio
pnpm prisma migrate  # Run migrations
```

### Frontend
```bash
cd frontend
pnpm dev              # Start dev server
pnpm build            # Build for production
pnpm preview          # Preview build
pnpm lint             # Run ESLint
pnpm format           # Format code
```

## Production Deployment

### Build

```bash
# Build both packages
pnpm build

# Or individually
cd backend && pnpm build
cd frontend && pnpm build
```

### Docker

```bash
# Build image
docker build -t tax-records:latest .

# Run container
docker run -p 3000:3000 -p 5173:5173 tax-records:latest
```

### Environment Setup

Create `.env` files with production values:

**Backend (.env)**
```
NODE_ENV=production
DATABASE_URL=postgresql://user:pass@host:5432/db
JWT_SECRET=your-secret-key-here
JWT_EXPIRE=7d
CORS_ORIGIN=https://yourdomain.com
LOG_LEVEL=info
```

**Frontend (.env)**
```
VITE_API_URL=https://api.yourdomain.com
```

## Security

- ✅ Password hashing with Argon2
- ✅ JWT-based authentication
- ✅ SQL injection protection (Prisma)
- ✅ CORS enabled
- ✅ Helmet for secure headers
- ✅ Input validation with Zod
- ✅ Audit logging
- ✅ Environment variable protection

## Testing

```bash
# Backend
cd backend && pnpm test

# Frontend
cd frontend && pnpm test
```

## Contributing

1. Create feature branch
2. Commit changes
3. Push to GitHub
4. Create Pull Request

## License

MIT

## Support

For issues and questions, please open an issue on GitHub.

- `/js`
  - `darkMode.js`: changes the color schemes of the website.
  - `getAssetIDSale.js`: get the id from the asset for the `Prodej` pop-up window
  - `jquery-3.6.1.min.js`: a jQuery library.
  - `restrictAddAsset.js`: disables the save button if the user inputs the purchase price lower than 80,000 CZK and at the same time sets the item as tangible.
  - `restrictAddAssetMinor.js`: disables the save button when the purchase price higher or equal to 80,000 CZK is inserted. Furthermore, the item is set to expense without an option to change.
  - `restrictEditAsset.js`: disables the option for editing assets in the `edit1.php`. `edit2.php` and `edit3.php`.
  - `showDepreciation.js`: sends data about the asset into the depreciation pop-up window which appears when clicking on the `Odpisy`.
  - `sort.js`: a sorting algorithm (bubble sort) covering the possibility of sorting document numbers, numerical values with a comma, `dd-mm--YY` dates, and other strings. The original is available at: [W3Schools](https://www.w3schools.com/howto/howto_js_sort_list.asp).
 
## /bc
The `/bc` directory includes a few important unmentioned files:

- `databaseConnection.php`: is the key script that maintains the communication with the database server. The data is saved in the `$connect` variable. This variable is then used for any database access.
- `edit1.php`, `edit2.php`, and `edit3.php`: are instances of the already existing `Records` classes. These are the pages related to item editing after they are created.

# Technologies

The used technologies are:

- HTML
- CSS (including Bootstrap)
- JavaScript
- PHP
- SQL
- Plotly (for graph generation)
- AJAX
- jQuery
