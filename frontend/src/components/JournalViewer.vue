<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { journalAPI, type JournalEntry, type JournalSummary } from '../../api/journal.api'

interface Props {
  journalType: 'general-ledger' | 'cash' | 'bank' | 'income' | 'expense' | 'demand-debt'
  title: string
}

const props = withDefaults(defineProps<Props>(), {
  journalType: 'general-ledger',
  title: 'Journal',
})

const entries = ref<JournalEntry[]>([])
const summary = ref<JournalSummary | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

const startDate = ref<string>(new Date(new Date().getFullYear(), 0, 1).toISOString().split('T')[0])
const endDate = ref<string>(new Date().toISOString().split('T')[0])

const totalDebit = computed(() => {
  return entries.value.reduce((sum, entry) => sum + (entry.debit || 0), 0)
})

const totalCredit = computed(() => {
  return entries.value.reduce((sum, entry) => sum + (entry.credit || 0), 0)
})

async function loadJournal() {
  loading.value = true
  error.value = null

  try {
    const start = new Date(startDate.value)
    const end = new Date(endDate.value)

    switch (props.journalType) {
      case 'general-ledger':
        entries.value = await journalAPI.getGeneralLedger(start, end)
        break
      case 'cash':
        entries.value = await journalAPI.getCashJournal(start, end)
        break
      case 'bank':
        entries.value = await journalAPI.getBankJournal(start, end)
        break
      case 'income':
        entries.value = await journalAPI.getIncomeJournal(start, end)
        break
      case 'expense':
        entries.value = await journalAPI.getExpenseJournal(start, end)
        break
      case 'demand-debt':
        entries.value = await journalAPI.getDemandDebtJournal(start, end)
        break
    }

    summary.value = await journalAPI.getJournalSummary(start, end)
  } catch (err: any) {
    error.value = err.message || 'Failed to load journal'
  } finally {
    loading.value = false
  }
}

function formatCurrency(value?: number) {
  if (!value) return '—'
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'EUR',
  }).format(value)
}

function exportToCSV() {
  const headers = ['Date', 'Document #', 'Name', 'Debit', 'Credit', 'Balance']
  const rows = entries.value.map(e => [
    e.date,
    e.documentNumber,
    e.name,
    e.debit || '',
    e.credit || '',
    e.balance,
  ])

  const csv = [headers, ...rows].map(row => row.map(cell => `"${cell}"`).join(',')).join('\n')
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `journal-${props.journalType}-${new Date().toISOString().split('T')[0]}.csv`
  link.click()
  URL.revokeObjectURL(url)
}

onMounted(() => {
  loadJournal()
})
</script>

<template>
  <div class="journal-viewer">
    <div class="journal-header">
      <h2>{{ title }}</h2>
      <button class="btn btn-secondary" @click="exportToCSV" :disabled="entries.length === 0">
        Export CSV
      </button>
    </div>

    <div v-if="error" class="alert alert-error">{{ error }}</div>

    <div class="filters">
      <div class="filter-group">
        <label for="start-date">Start Date:</label>
        <input
          id="start-date"
          v-model="startDate"
          type="date"
          @change="loadJournal"
          :disabled="loading"
        />
      </div>
      <div class="filter-group">
        <label for="end-date">End Date:</label>
        <input
          id="end-date"
          v-model="endDate"
          type="date"
          @change="loadJournal"
          :disabled="loading"
        />
      </div>
    </div>

    <div v-if="loading" class="loading">Loading journal...</div>

    <div v-else-if="entries.length === 0" class="no-data">No entries found</div>

    <div v-else class="journal-container">
      <table class="journal-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Document #</th>
            <th>Name</th>
            <th class="number">Debit</th>
            <th class="number">Credit</th>
            <th class="number">Balance</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(entry, idx) in entries" :key="idx">
            <td>{{ entry.date }}</td>
            <td>{{ entry.documentNumber }}</td>
            <td>{{ entry.name }}</td>
            <td class="number">{{ formatCurrency(entry.debit) }}</td>
            <td class="number">{{ formatCurrency(entry.credit) }}</td>
            <td class="number">{{ formatCurrency(entry.balance) }}</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="font-weight: 600">Totals:</td>
            <td class="number">{{ formatCurrency(totalDebit) }}</td>
            <td class="number">{{ formatCurrency(totalCredit) }}</td>
            <td class="number">{{ formatCurrency(totalCredit - totalDebit) }}</td>
          </tr>
        </tfoot>
      </table>

      <div v-if="summary" class="summary-cards">
        <div class="summary-card">
          <div class="label">Total Income</div>
          <div class="value">{{ formatCurrency(summary.totalIncome) }}</div>
        </div>
        <div class="summary-card">
          <div class="label">Total Expense</div>
          <div class="value">{{ formatCurrency(summary.totalExpense) }}</div>
        </div>
        <div class="summary-card">
          <div class="label">Net</div>
          <div class="value">{{ formatCurrency(summary.totalIncome - summary.totalExpense) }}</div>
        </div>
        <div class="summary-card">
          <div class="label">Cash Total</div>
          <div class="value">{{ formatCurrency(summary.cashTotal) }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.journal-viewer {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.journal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.journal-header h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #1f2937;
}

.alert {
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1.5rem;
}

.alert-error {
  background-color: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-group label {
  font-size: 0.9rem;
  font-weight: 500;
}

.filter-group input {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
}

.loading,
.no-data {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}

.journal-container {
  overflow-x: auto;
}

.journal-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.5rem;
}

.journal-table th,
.journal-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.journal-table th {
  background-color: #f3f4f6;
  font-weight: 600;
  color: #374151;
}

.journal-table td.number,
.journal-table th.number {
  text-align: right;
  font-family: 'Courier New', monospace;
}

.journal-table tbody tr:hover {
  background-color: #f9fafb;
}

.journal-table tfoot tr {
  background-color: #f3f4f6;
  font-weight: 600;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.summary-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 1rem;
  border-radius: 6px;
  text-align: center;
}

.summary-card .label {
  font-size: 0.9rem;
  opacity: 0.9;
  margin-bottom: 0.5rem;
}

.summary-card .value {
  font-size: 1.5rem;
  font-weight: 600;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary {
  background-color: #6b7280;
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background-color: #4b5563;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
