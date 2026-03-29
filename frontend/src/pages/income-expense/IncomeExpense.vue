<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import RegressionChart from '../../components/RegressionChart.vue'
import { incomeExpenseAPI, type IncomeExpense, type IncomeExpenseInput } from '../../api/income-expense'

// Data
const records = ref<IncomeExpense[]>([])
const loading = ref(false)
const showForm = ref(false)
const selectedYear = ref(new Date().getFullYear())
const filterType = ref<'all' | 'income' | 'expense'>('all')
const error = ref<string | null>(null)
const success = ref<string | null>(null)

// Form data
const formData = ref<IncomeExpenseInput>({
  name: '',
  date: new Date().toISOString().split('T')[0],
  type: 'income',
  amount: 0,
  taxType: 'taxable',
  paymentMethod: 'bank_transfer',
  description: '',
})

const formErrors = ref<Record<string, string>>({})

// Computed properties
const filteredRecords = computed(() => {
  return records.value.filter(record => {
    if (filterType.value !== 'all' && record.type !== filterType.value) {
      return false
    }
    const recordYear = new Date(record.date).getFullYear()
    return recordYear === selectedYear.value
  })
})

const summary = computed(() => {
  let totalIncome = 0
  let totalExpense = 0

  filteredRecords.value.forEach(record => {
    const amount = record.amount / 100 // Convert from cents
    if (record.type === 'income') {
      totalIncome += amount
    } else {
      totalExpense += amount
    }
  })

  return {
    totalIncome,
    totalExpense,
    net: totalIncome - totalExpense,
  }
})

const chartData = computed(() => {
  return filteredRecords.value
    .sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
    .map(record => ({
      date: record.date,
      value: record.amount / 100,
    }))
})

// Methods
async function fetchRecords() {
  loading.value = true
  error.value = null

  try {
    const type = filterType.value === 'all' ? undefined : (filterType.value as 'income' | 'expense')
    records.value = await incomeExpenseAPI.list(selectedYear.value, type)
  } catch (err: any) {
    error.value = err.message || 'Failed to load records'
  } finally {
    loading.value = false
  }
}

async function handleSubmit() {
  formErrors.value = {}
  error.value = null
  success.value = null

  // Basic validation
  if (!formData.value.name.trim()) {
    formErrors.value.name = 'Name is required'
  }
  if (!formData.value.amount || formData.value.amount <= 0) {
    formErrors.value.amount = 'Amount must be greater than 0'
  }

  if (Object.keys(formErrors.value).length > 0) {
    return
  }

  try {
    // Backend handles conversion to cents, just send the amount as-is
    const input: IncomeExpenseInput = {
      ...formData.value,
    }

    await incomeExpenseAPI.create(input)
    success.value = 'Record created successfully!'
    
    // Reset form
    formData.value = {
      name: '',
      date: new Date().toISOString().split('T')[0],
      type: 'income',
      amount: 0,
      taxType: 'taxable',
      paymentMethod: 'bank_transfer',
      description: '',
    }
    showForm.value = false

    // Reload records
    await fetchRecords()
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to create record'
  }
}

async function deleteRecord(id: number) {
  if (!confirm('Are you sure you want to delete this record?')) {
    return
  }

  try {
    await incomeExpenseAPI.delete(id)
    success.value = 'Record deleted successfully!'
    await fetchRecords()
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to delete record'
  }
}

function formatCurrency(value: number) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'EUR',
  }).format(value / 100)
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

// Lifecycle
onMounted(() => {
  fetchRecords()
})
</script>

<template>
  <div class="page-container">
    <div class="header">
      <h1>Příjmy a Výdaje</h1>
      <button class="btn btn-primary" @click="showForm = !showForm">
        {{ showForm ? 'Cancel' : 'Add Record' }}
      </button>
    </div>

    <!-- Messages -->
    <div v-if="error" class="alert alert-error">{{ error }}</div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <!-- Add Record Form -->
    <div v-if="showForm" class="form-container">
      <h2>Add New Record</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="name">Name *</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            placeholder="Enter record name"
            :class="{ 'input-error': formErrors.name }"
          />
          <span v-if="formErrors.name" class="error-text">{{ formErrors.name }}</span>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="date">Date *</label>
            <input
              id="date"
              v-model="formData.date"
              type="date"
              :class="{ 'input-error': formErrors.date }"
            />
          </div>

          <div class="form-group">
            <label for="type">Type *</label>
            <select
              id="type"
              v-model="formData.type"
              :class="{ 'input-error': formErrors.type }"
            >
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>

          <div class="form-group">
            <label for="amount">Amount (€) *</label>
            <input
              id="amount"
              v-model.number="formData.amount"
              type="number"
              step="0.01"
              placeholder="0.00"
              :class="{ 'input-error': formErrors.amount }"
            />
            <span v-if="formErrors.amount" class="error-text">{{ formErrors.amount }}</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="taxType">Tax Type</label>
            <select id="taxType" v-model="formData.taxType">
              <option value="taxable">Taxable</option>
              <option value="non-taxable">Non-taxable</option>
            </select>
          </div>

          <div class="form-group">
            <label for="paymentMethod">Payment Method</label>
            <input
              id="paymentMethod"
              v-model="formData.paymentMethod"
              type="text"
              placeholder="e.g., Bank Transfer"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="formData.description"
            placeholder="Optional notes"
            rows="3"
          />
        </div>

        <button type="submit" class="btn btn-primary">Create Record</button>
      </form>
    </div>

    <!-- Filters -->
    <div class="filters">
      <div class="filter-group">
        <label for="year">Year:</label>
        <select id="year" v-model.number="selectedYear" @change="fetchRecords">
          <option v-for="year in [2023, 2024, 2025, 2026]" :key="year" :value="year">
            {{ year }}
          </option>
        </select>
      </div>

      <div class="filter-group">
        <label for="filter">Type:</label>
        <select id="filter" v-model="filterType" @change="fetchRecords">
          <option value="all">All</option>
          <option value="income">Income</option>
          <option value="expense">Expense</option>
        </select>
      </div>
    </div>

    <!-- Summary -->
    <div class="summary-grid">
      <div class="summary-card income">
        <h3>Total Income</h3>
        <p class="amount">{{ formatCurrency(summary.totalIncome) }}</p>
      </div>
      <div class="summary-card expense">
        <h3>Total Expense</h3>
        <p class="amount">{{ formatCurrency(summary.totalExpense) }}</p>
      </div>
      <div class="summary-card net" :class="{ negative: summary.net < 0 }">
        <h3>Net</h3>
        <p class="amount">{{ formatCurrency(summary.net) }}</p>
      </div>
    </div>

    <!-- Chart -->
    <div v-if="chartData.length > 0" class="chart-section">
      <RegressionChart
        :data="chartData"
        :title="`${filterType === 'all' ? 'All' : filterType} Trend with Linear Regression`"
        :label="filterType === 'all' ? 'Amount (€)' : `${filterType} (€)`"
        :color="filterType === 'income' ? '#10B981' : filterType === 'expense' ? '#EF4444' : '#3B82F6'"
      />
    </div>

    <!-- Records Table -->
    <div class="table-section">
      <h2>Records</h2>

      <div v-if="loading" class="loading">Loading records...</div>

      <div v-else-if="filteredRecords.length === 0" class="no-data">
        No records found for the selected filters
      </div>

      <table v-else class="records-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Tax Type</th>
            <th>Payment</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in filteredRecords" :key="record.id" :class="record.type">
            <td>{{ formatDate(record.date) }}</td>
            <td>{{ record.name }}</td>
            <td>
              <span class="badge" :class="record.type">
                {{ record.type === 'income' ? 'Income' : 'Expense' }}
              </span>
            </td>
            <td class="amount">{{ formatCurrency(record.amount) }}</td>
            <td>{{ record.taxType === 'taxable' ? 'Taxable' : 'Non-taxable' }}</td>
            <td>{{ record.paymentMethod }}</td>
            <td>
              <button class="btn btn-sm btn-danger" @click="deleteRecord(record.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.page-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

h1 {
  margin: 0;
  font-size: 2rem;
  color: #1f2937;
}

h2 {
  font-size: 1.5rem;
  color: #374151;
  margin: 1.5rem 0 1rem 0;
}

/* Alerts */
.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
}

.alert-error {
  background-color: #fee2e2;
  border: 1px solid #fecaca;
  color: #991b1b;
}

.alert-success {
  background-color: #dcfce7;
  border: 1px solid #bbf7d0;
  color: #166534;
}

/* Form */
.form-container {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #374151;
  font-size: 0.95rem;
}

input,
select,
textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.95rem;
  font-family: inherit;
  transition: border-color 0.2s;
}

input:focus,
select:focus,
textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input.input-error,
select.input-error,
textarea.input-error {
  border-color: #ef4444;
}

.error-text {
  display: block;
  margin-top: 0.25rem;
  font-size: 0.85rem;
  color: #ef4444;
}

/* Buttons */
.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.85rem;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
}

.btn-danger:hover {
  background-color: #dc2626;
}

/* Filters */
.filters {
  display: flex;
  gap: 2rem;
  margin-bottom: 2rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-group label {
  margin: 0;
  font-size: 0.95rem;
}

.filter-group select {
  width: auto;
  min-width: 150px;
}

/* Summary */
.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.summary-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #3b82f6;
}

.summary-card.income {
  border-left-color: #10b981;
}

.summary-card.expense {
  border-left-color: #ef4444;
}

.summary-card.net {
  border-left-color: #f59e0b;
}

.summary-card.net.negative {
  border-left-color: #ef4444;
}

.summary-card h3 {
  margin: 0 0 0.5rem 0;
  font-size: 0.95rem;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.summary-card .amount {
  margin: 0;
  font-size: 1.75rem;
  font-weight: bold;
  color: #1f2937;
}

/* Chart */
.chart-section {
  margin-bottom: 2rem;
}

/* Table */
.table-section {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.records-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

.records-table thead {
  background: #f3f4f6;
  border-bottom: 2px solid #e5e7eb;
}

.records-table th {
  padding: 0.75rem;
  text-align: left;
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
}

.records-table tbody tr {
  border-bottom: 1px solid #e5e7eb;
  transition: background-color 0.2s;
}

.records-table tbody tr:hover {
  background-color: #f9fafb;
}

.records-table td {
  padding: 1rem 0.75rem;
  color: #374151;
}

.records-table .amount {
  font-weight: 600;
  color: #1f2937;
}

.badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 500;
}

.badge.income {
  background-color: #dcfce7;
  color: #166534;
}

.badge.expense {
  background-color: #fee2e2;
  color: #991b1b;
}

/* Loading & No Data */
.loading,
.no-data {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
  font-size: 1rem;
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    gap: 1rem;
  }

  .filters {
    flex-direction: column;
    gap: 1rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .records-table {
    font-size: 0.85rem;
  }

  .records-table th,
  .records-table td {
    padding: 0.5rem;
  }
}
</style>
