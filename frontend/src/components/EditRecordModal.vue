<script setup lang="ts">
import { ref, watch } from 'vue'
import { type IncomeExpense } from '../../api/income-expense'

interface Props {
  isOpen?: boolean
  record?: IncomeExpense | null
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
  date: new Date().toISOString().split('T')[0],
  type: 'income' as 'income' | 'expense',
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
        date: new Date(record.date).toISOString().split('T')[0],
        type: record.type as 'income' | 'expense',
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
            <label for="date">Date *</label>
            <input id="date" v-model="formData.date" type="date" />
          </div>

          <div class="form-group">
            <label for="type">Type *</label>
            <select id="type" v-model="formData.type">
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
            rows="2"
          />
        </div>
      </form>

      <div class="modal-footer">
        <button class="btn btn-secondary" @click="close">Cancel</button>
        <button class="btn btn-primary" :disabled="loading" @click="handleSubmit">
          {{ loading ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
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
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
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
}

.close-button {
  background: none;
  border: none;
  font-size: 2rem;
  cursor: pointer;
  color: #6b7280;
  padding: 0;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-button:hover {
  color: #1f2937;
}

.modal-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  font-size: 0.9rem;
  color: #374151;
}

input,
select,
textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.9rem;
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
  color: #ef4444;
  font-size: 0.8rem;
  margin-top: 0.25rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 500;
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

.btn-secondary {
  background-color: #e5e7eb;
  color: #374151;
}

.btn-secondary:hover {
  background-color: #d1d5db;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
