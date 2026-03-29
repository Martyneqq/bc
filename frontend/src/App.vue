<template>
  <div id="app" class="app-container">
    <header class="navbar">
      <div class="nav-brand">Tax Records</div>
      <nav class="nav-links">
        <router-link v-if="!authStore.isAuthenticated" to="/login">Login</router-link>
        <router-link v-if="!authStore.isAuthenticated" to="/register">Register</router-link>
        <router-link v-if="authStore.isAuthenticated" to="/">Dashboard</router-link>
        <button v-if="authStore.isAuthenticated" @click="authStore.logout" class="btn-logout">
          Logout
        </button>
      </nav>
    </header>

    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from './stores/auth.store'

const authStore = useAuthStore()
</script>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.navbar {
  background-color: #2c3e50;
  color: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-brand {
  font-size: 1.5rem;
  font-weight: bold;
}

.nav-links {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.nav-links a {
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.nav-links a:hover,
.nav-links a.router-link-active {
  background-color: #34495e;
}

.btn-logout {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-logout:hover {
  background-color: #c0392b;
}

.main-content {
  flex: 1;
  padding: 2rem;
  background-color: #ecf0f1;
}
</style>
