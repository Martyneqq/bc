<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { journalAPI, type JournalSummary } from '../../api/journal.api'

const summary = ref<JournalSummary | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

async function loadStats() {
  loading.value = true
  error.value = null

  try {
    const today = new Date()
    const startOfYear = new Date(today.getFullYear(), 0, 1)
    summary.value = await journalAPI.getJournalSummary(startOfYear, today)
  } catch (err: any) {
    error.value = err.message || 'Failed to load statistics'
  } finally {
    loading.value = false
  }
}

function formatCurrency(value: number): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'EUR',
  }).format(value / 100)
}

onMounted(() => {
  loadStats()
})
</script>

<template>
  <div class="quick-stats">
    <div v-if="error" class="alert alert-error">{{ error }}</div>

    <div v-if="loading" class="loading">Loading statistics...</div>

    <div v-else-if="summary" class="stats-grid">
      <div class="stat-card income">
        <div class="stat-icon">📈</div>
        <div class="stat-label">Total Income (YTD)</div>
        <div class="stat-value">{{ formatCurrency(summary.totalIncome) }}</div>
      </div>

      <div class="stat-card expense">
        <div class="stat-icon">📉</div>
        <div class="stat-label">Total Expense (YTD)</div>
        <div class="stat-value">{{ formatCurrency(summary.totalExpense) }}</div>
      </div>

      <div class="stat-card net" :class="{ negative: summary.totalIncome - summary.totalExpense < 0 }">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Net (YTD)</div>
        <div class="stat-value">{{ formatCurrency(summary.totalIncome - summary.totalExpense) }}</div>
      </div>

      <div class="stat-card cash">
        <div class="stat-icon">💵</div>
        <div class="stat-label">Cash Total</div>
        <div class="stat-value">{{ formatCurrency(summary.cashTotal) }}</div>
      </div>

      <div class="stat-card bank">
        <div class="stat-icon">🏦</div>
        <div class="stat-label">Bank Total</div>
        <div class="stat-value">{{ formatCurrency(summary.bankTotal) }}</div>
      </div>

      <div class="stat-card unpaid">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Unpaid Demands/Debts</div>
        <div class="stat-value">{{ summary.demandsUnpaid + summary.debtsUnpaid }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.quick-stats {
  width: 100%;
}

.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
}

.alert-error {
  background-color: #fee2e2;
  border: 1px solid #fecaca;
  color: #991b1b;
}

.loading {
  text-align: center;
  color: #6b7280;
  padding: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.stat-card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #3b82f6;
}

.stat-card.income {
  border-left-color: #10b981;
}

.stat-card.expense {
  border-left-color: #ef4444;
}

.stat-card.net {
  border-left-color: #3b82f6;
}

.stat-card.net.negative {
  border-left-color: #ef4444;
}

.stat-card.cash {
  border-left-color: #8b5cf6;
}

.stat-card.bank {
  border-left-color: #0ea5e9;
}

.stat-card.unpaid {
  border-left-color: #f59e0b;
}

.stat-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.stat-label {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1f2937;
}
</style>
