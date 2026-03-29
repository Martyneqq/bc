FROM node:20-slim

WORKDIR /app

# Copy workspace and package files
COPY package.json pnpm-workspace.yaml ./
COPY backend/ ./backend/
COPY frontend/package.json ./frontend/

# Install pnpm globally and install dependencies with scripts allowed
RUN npm install -g pnpm && pnpm install --recursive --allow-scripts

# Generate Prisma client
RUN cd /app/backend && pnpm prisma generate

# Expose port
EXPOSE 3000

# Start backend server
CMD ["sh", "-c", "cd /app/backend && pnpm dev"]
