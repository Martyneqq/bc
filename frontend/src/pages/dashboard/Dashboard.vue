<template>
  <div class="dashboard-container">
    <div class="welcome-section">
      <h1>Welcome, {{ authStore.user?.username }}!</h1>
      <p>Manage your tax records and financial data</p>
    </div>

    <div class="cards-grid">
      <router-link to="/income-expense" class="card">
        <div class="card-icon">💰</div>
        <h3>Income & Expense</h3>
        <p>Manage your income and expense records</p>
      </router-link>

      <router-link to="/assets" class="card">
        <div class="card-icon">🏢</div>
        <h3>Assets</h3>
        <p>Track your fixed and minor assets</p>
      </router-link>

      <router-link to="/demands-debts" class="card">
        <div class="card-icon">📋</div>
        <h3>Demands & Debts</h3>
        <p>Monitor your receivables and payables</p>
      </router-link>

      <router-link to="/profile" class="card">
        <div class="card-icon">👤</div>
        <h3>Profile</h3>
        <p>Manage your account and profile</p>
      </router-link>

      <router-link to="/settings" class="card">
        <div class="card-icon">⚙️</div>
        <h3>Settings</h3>
        <p>Customize your experience</p>
      </router-link>

      <router-link to="/help" class="card">
        <div class="card-icon">❓</div>
        <h3>Help & Documentation</h3>
        <p>Learn how to use the app</p>
      </router-link>

      <router-link to="/journals" class="card">
        <div class="card-icon">📊</div>
        <h3>Journals & Ledgers</h3>
        <p>View accounting journals and ledgers</p>
      </router-link>

      <router-link to="/audit" class="card">
        <div class="card-icon">📋</div>
        <h3>Audit Log</h3>
        <p>View all changes and audit trail</p>
      </router-link>
    </div>

    <div class="stats-section">
      <h2>Quick Stats</h2>
      <QuickStats />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store'
import QuickStats from '../../components/QuickStats.vue'

const authStore = useAuthStore()
</script>

<style scoped>
.dashboard-container {
  max-width: 1200px;
  margin: 0 auto;
}

.welcome-section {
  margin-bottom: 2rem;
}

.welcome-section h1 {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.welcome-section p {
  font-size: 1.1rem;
  color: #666;
}

.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  text-decoration: none;
  color: inherit;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.card-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.card h3 {
  font-size: 1.25rem;
  margin-bottom: 0.5rem;
  color: #2c3e50;
}

.card p {
  color: #666;
  font-size: 0.95rem;
}

.stats-section {
  margin-top: 2rem;
}

.stats-section h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #2c3e50;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.stat-card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.stat-value {
  font-size: 2rem;
  font-weight: bold;
  color: #667eea;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: #666;
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .welcome-section h1 {
    font-size: 1.8rem;
  }

  .cards-grid {
    grid-template-columns: 1fr;
  }
}
</style>
