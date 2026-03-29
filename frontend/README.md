# Frontend Development

## Available Scripts

### `pnpm dev`
Runs the app in development mode at http://localhost:5173

### `pnpm build`
Builds the app for production

### `pnpm preview`
Preview the production build locally

## Project Structure

```
src/
├── components/          # Reusable UI components
├── pages/              # Page components (routed pages)
├── stores/             # Pinia state management
├── api/                # API client layer
├── router/             # Vue Router configuration
├── styles/             # Global styles
├── utils/              # Helper functions
├── App.vue             # Root component
└── main.ts             # Application entry point
```

## Environment Variables

Create `.env.local` for development overrides:

```
VITE_API_URL=http://localhost:3000
```

## Tools & Technology

- **Vue 3** - Progressive framework
- **TypeScript** - Type safety
- **Vite** - Build tool & dev server
- **Pinia** - State management
- **Vue Router** - Routing
- **Axios** - HTTP client
- **Vitest** - Testing framework

## Code Style

- Use `<script setup>` syntax
- TypeScript for all new code
- Follow Vue 3 composition API patterns
- Use Pinia stores for state
- Components in PascalCase
- Props are explicitly typed
