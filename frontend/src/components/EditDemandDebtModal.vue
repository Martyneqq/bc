<script setup lang="ts">
import { ref, watch } from 'vue'
import { type DemandDebt } from '../../api/demand-debt'

interface Props {
  isOpen?: boolean
  record?: DemandDebt | null
}

interface Emits {
  (e: 'close'): void
  (e: 'save', data: any): void
}

const props = withDefaults(defineProps<Props>(), {
  isOpen: false,
})

const emit = defineEmits<Emits>()

const formData = ref({
  name: '',
  company: '',
  type: 'demand' as 'demand' | 'debt',
  dateCreated: new Date().toISOString().split('T')[0],
  dateDue: new Date().toISOString().split('T')[0],
  amount: 0,
  taxType: 'taxable' as 'taxable' | 'non-taxable',
  paymentMethod: '',
  description: '',
})

const formErrors = ref<Record<string, string>>({})
const loading = ref(false)

watch(
  () => props.record,
  (record) => {
    if (record) {
      formData.value = {
        name: record.name,
        company: record.company,
        type: record.type as 'demand' | 'debt',
        dateCreated: new Date(record.dateCreated).toISOString().split('T')[0],
        dateDue: new Date(record.dateDue).toISOString().split('T')[0],
        amount: record.amount / 100,
        taxType: record.taxType as 'taxable' | 'non-taxable',
        paymentMethod: record.paymentMethod,
        description: record.description || '',
      }
    }
  },
)

function validate(): boolean {
  formErrors.value = {}

  if (!formData.value.name.trim()) {
    formErrors.value.name = 'Name is required'
  }
  if (!formData.value.company.trim()) {
    formErrors.value.company = 'Company is required'
  }
  if (!formData.value.amount || formData.value.amount <= 0) {
    formErrors.value.amount = 'Amount must be greater than 0'
  }

  return Object.keys(formErrors.value).length === 0
}

async function handleSubmit() {
  if (!validate()) return

  loading.value = true
  try {
    emit('save', { ...formData.value })
    close()
  } finally {
    loading.value = false
  }
}

function close() {
  emit('close')
}
</script>

<template>
  <div v-if="isOpen" class="modal-overlay" @click="close">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>{{ record ? 'Edit Record' : 'New Record' }}</h2>
        <button class="close-button" @click="close">&times;</button>
      </div>

      <form class="modal-body" @submit.prevent="handleSubmit">
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
        </div>

        <div class="form-row">
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

          <div class="form-group">
            <label for="taxType">Tax Type</label>
            <select id="taxType" v-model="formData.taxType">
              <option value="taxable">Taxable</option>
              <option value="non-taxable">Non-taxable</option>
            </select>
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
        </div>

        <div class="form-group">
          <label for="paymentMethod">Payment Method</label>
          <input id="paymentMethod" v-model="formData.paymentMethod" type="text" placeholder="e.g., Bank Transfer" />
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" v-model="formData.description" placeholder="Notes" rows="3" />
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.25rem;
  color: #1f2937;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #6b7280;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.close-button:hover {
  background-color: #f3f4f6;
}

.modal-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #374151;
}

input[type="text"],
input[type="date"],
input[type="number"],
select,
textarea {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 0.875rem;
  font-family: inherit;
  transition: border-color 0.2s;
}

input[type="text"]:focus,
input[type="date"]:focus,
input[type="number"]:focus,
select:focus,
textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input[type="checkbox"] {
  margin-right: 0.5rem;
}

.input-error {
  border-color: #ef4444 !important;
  background-color: #fef2f2;
}

.error-text {
  display: block;
  color: #dc2626;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #2563eb;
}

.btn-primary:disabled {
  background-color: #9ca3af;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #e5e7eb;
  color: #1f2937;
}

.btn-secondary:hover {
  background-color: #d1d5db;
}
</style>
