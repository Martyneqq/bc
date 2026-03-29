<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { demandDebtAPI, type DemandDebt, type DemandDebtInput } from '../../api/demand-debt'

const records = ref<DemandDebt[]>([])
const loading = ref(false)
const showForm = ref(false)
const filterType = ref<'all' | 'demand' | 'debt'>('all')
const filterPaid = ref<'all' | 'paid' | 'unpaid'>('unpaid')
const error = ref<string | null>(null)
const success = ref<string | null>(null)

const formData = ref<DemandDebtInput>({
  name: '',
  company: '',
  type: 'demand',
  dateCreated: new Date().toISOString().split('T')[0],
  dateDue: new Date().toISOString().split('T')[0],
  amount: 0,
  taxType: 'taxable',
  paymentMethod: 'bank_transfer',
  description: '',
})

const formErrors = ref<Record<string, string>>({})

const filteredRecords = computed(() => {
  return records.value.filter(record => {
    if (filterType.value !== 'all' && record.type !== filterType.value) {
      return false
    }
    if (filterPaid.value === 'paid' && !record.isPaid) {
      return false
    }
    if (filterPaid.value === 'unpaid' && record.isPaid) {
      return false
    }
    return true
  })
})

async function fetchRecords() {
  loading.value = true
  error.value = null
  try {
    const type = filterType.value === 'all' ? undefined : (filterType.value as 'demand' | 'debt')
    const isPaid = filterPaid.value === 'all' ? undefined : filterPaid.value === 'paid'
    records.value = await demandDebtAPI.list(type, isPaid)
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

  if (!formData.value.name.trim()) {
    formErrors.value.name = 'Name is required'
  }
  if (!formData.value.company.trim()) {
    formErrors.value.company = 'Company is required'
  }
  if (!formData.value.amount || formData.value.amount <= 0) {
    formErrors.value.amount = 'Amount must be greater than 0'
  }

  if (Object.keys(formErrors.value).length > 0) {
    return
  }

  try {
    const dateCreated = new Date(formData.value.dateCreated)
    dateCreated.setHours(12, 0, 0, 0)
    const dateDue = new Date(formData.value.dateDue)
    dateDue.setHours(12, 0, 0, 0)

    const input: DemandDebtInput = {
      ...formData.value,
      dateCreated: dateCreated.toISOString(),
      dateDue: dateDue.toISOString(),
    }

    await demandDebtAPI.create(input)
    success.value = 'Record created successfully!'

    formData.value = {
      name: '',
      company: '',
      type: 'demand',
      dateCreated: new Date().toISOString().split('T')[0],
      dateDue: new Date().toISOString().split('T')[0],
      amount: 0,
      taxType: 'taxable',
      paymentMethod: 'bank_transfer',
      description: '',
    }
    showForm.value = false
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
    await demandDebtAPI.delete(id)
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

onMounted(() => {
  fetchRecords()
})
</script>

<template>
  <div class="page-container">
    <div class="header">
      <h1>Pohledávky a Dluhy</h1>
      <button class="btn btn-primary" @click="showForm = !showForm">
        {{ showForm ? 'Cancel' : 'Add Record' }}
      </button>
    </div>

    <div v-if="error" class="alert alert-error">{{ error }}</div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <div v-if="showForm" class="form-container">
      <h2>Add New Record</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="name">Name *</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            placeholder="Record name"
            :class="{ 'input-error': formErrors.name }"
          />
          <span v-if="formErrors.name" class="error-text">{{ formErrors.name }}</span>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="company">Company *</label>
            <input
              id="company"
              v-model="formData.company"
              type="text"
              placeholder="Company name"
              :class="{ 'input-error': formErrors.company }"
            />
            <span v-if="formErrors.company" class="error-text">{{ formErrors.company }}</span>
          </div>

          <div class="form-group">
            <label for="type">Type *</label>
            <select id="type" v-model="formData.type">
              <option value="demand">Demand (Money Owed to Me)</option>
              <option value="debt">Debt (Money I Owe)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="amount">Amount (€) *</label>
            <input
              id="amount"
              v-model.number="formData.amount"
              type="number"
              step="0.01"
              :class="{ 'input-error': formErrors.amount }"
            />
            <span v-if="formErrors.amount" class="error-text">{{ formErrors.amount }}</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="dateCreated">Date Created *</label>
            <input id="dateCreated" v-model="formData.dateCreated" type="date" />
          </div>

          <div class="form-group">
            <label for="dateDue">Due Date *</label>
            <input id="dateDue" v-model="formData.dateDue" type="date" />
          </div>

          <div class="form-group">
            <label for="taxType">Tax Type</label>
            <select id="taxType" v-model="formData.taxType">
              <option value="taxable">Taxable</option>
              <option value="non-taxable">Non-taxable</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="paymentMethod">Payment Method</label>
          <input id="paymentMethod" v-model="formData.paymentMethod" type="text" placeholder="e.g., Bank Transfer" />
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" v-model="formData.description" placeholder="Notes" rows="3" />
        </div>

        <button type="submit" class="btn btn-primary">Create Record</button>
      </form>
    </div>

    <div class="filters">
      <div class="filter-group">
        <label for="type-filter">Type:</label>
        <select id="type-filter" v-model="filterType" @change="fetchRecords">
          <option value="all">All</option>
          <option value="demand">Demands</option>
          <option value="debt">Debts</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="paid-filter">Status:</label>
        <select id="paid-filter" v-model="filterPaid" @change="fetchRecords">
          <option value="unpaid">Unpaid</option>
          <option value="paid">Paid</option>
          <option value="all">All</option>
        </select>
      </div>
    </div>

    <div class="table-section">
      <h2>Records</h2>

      <div v-if="loading" class="loading">Loading...</div>

      <div v-else-if="filteredRecords.length === 0" class="no-data">No records found</div>

      <table v-else class="records-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Company</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Created</th>
            <th>Due</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in filteredRecords" :key="record.id">
            <td>{{ record.name }}</td>
            <td>{{ record.company }}</td>
            <td>
              <span class="badge" :class="record.type">
                {{ record.type === 'demand' ? 'Demand' : 'Debt' }}
              </span>
            </td>
            <td class="amount">{{ formatCurrency(record.amount) }}</td>
            <td>{{ formatDate(record.dateCreated) }}</td>
            <td>{{ formatDate(record.dateDue) }}</td>
            <td>
              <span class="status" :class="record.isPaid ? 'paid' : 'unpaid'">
                {{ record.isPaid ? 'Paid' : 'Unpaid' }}
              </span>
            </td>
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
}

input:focus,
select:focus,
textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input.input-error {
  border-color: #ef4444;
}

.error-text {
  display: block;
  margin-top: 0.25rem;
  font-size: 0.85rem;
  color: #ef4444;
}

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

.filters {
  display: flex;
  gap: 2rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
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
}

.badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 500;
}

.badge.demand {
  background-color: #dbeafe;
  color: #1e40af;
}

.badge.debt {
  background-color: #fce7f3;
  color: #831843;
}

.status {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 500;
}

.status.paid {
  background-color: #dcfce7;
  color: #166534;
}

.status.unpaid {
  background-color: #fee2e2;
  color: #991b1b;
}

.loading,
.no-data {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}
</style>
