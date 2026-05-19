FROM node:20-slim

WORKDIR /app

# Copy workspace and package files
COPY package.json pnpm-workspace.yaml pnpm-lock.yaml ./
COPY frontend/ ./frontend/
COPY backend/package.json ./backend/

# Install pnpm globally and install dependencies
RUN npm install -g pnpm && pnpm install --recursive

# Expose port
EXPOSE 5173

# Start frontend development server with host binding
CMD ["sh", "-c", "cd /app/frontend && pnpm dev --host 0.0.0.0"]
