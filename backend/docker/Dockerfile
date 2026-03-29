FROM node:20-alpine

WORKDIR /app

# Copy package files
COPY package.json pnpm-lock.yaml* ./

# Install dependencies
RUN npm install -g pnpm && pnpm install --frozen-lockfile

# Copy backend source
COPY backend/ ./

# Generate Prisma client
RUN pnpm prisma generate

# Expose port
EXPOSE 3000

# Start server
CMD ["pnpm", "dev"]
