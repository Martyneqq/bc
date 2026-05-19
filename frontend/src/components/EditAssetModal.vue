<script setup lang="ts">
import { ref, watch } from 'vue'
import { type Asset } from '../../api/asset'

interface Props {
  isOpen?: boolean
  record?: Asset | null
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
  dateAcquired: new Date().toISOString().split('T')[0],
  initialValue: 0,
  type: 'tangible' as 'tangible' | 'intangible' | 'minor_asset',
  depreciationGroup: 1,
  depreciationMethod: 'linear' as 'linear' | 'accelerated',
  taxDeductible: true,
  paymentMethod: '',
  description: '',
  dateDisposed: '',
})

const formErrors = ref<Record<string, string>>({})
const loading = ref(false)

watch(
  () => props.record,
  (record) => {
    if (record) {
      formData.value = {
        name: record.name,
        dateAcquired: new Date(record.dateAcquired).toISOString().split('T')[0],
        initialValue: record.initialValue / 100,
        type: record.type as 'tangible' | 'intangible' | 'minor_asset',
        depreciationGroup: record.depreciationGroup,
        depreciationMethod: record.depreciationMethod as 'linear' | 'accelerated',
        taxDeductible: record.taxDeductible,
        paymentMethod: record.paymentMethod,
        description: record.description || '',
        dateDisposed: record.dateDisposed ? new Date(record.dateDisposed).toISOString().split('T')[0] : '',
      }
    }
  },
)

function validate(): boolean {
  formErrors.value = {}

  if (!formData.value.name.trim()) {
    formErrors.value.name = 'Name is required'
  }
  if (!formData.value.initialValue || formData.value.initialValue <= 0) {
    formErrors.value.initialValue = 'Value must be greater than 0'
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
        <h2>{{ record ? 'Edit Asset' : 'New Asset' }}</h2>
        <button class="close-button" @click="close">&times;</button>
      </div>

      <form class="modal-body" @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="name">Name *</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            placeholder="Enter asset name"
            :class="{ 'input-error': formErrors.name }"
          />
          <span v-if="formErrors.name" class="error-text">{{ formErrors.name }}</span>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="type">Type *</label>
            <select id="type" v-model="formData.type">
              <option value="tangible">Tangible</option>
              <option value="intangible">Intangible</option>
              <option value="minor_asset">Minor Asset</option>
            </select>
          </div>

          <div class="form-group">
            <label for="initialValue">Initial Value (€) *</label>
            <input
              id="initialValue"
              v-model.number="formData.initialValue"
              type="number"
              step="0.01"
              :class="{ 'input-error': formErrors.initialValue }"
            />
            <span v-if="formErrors.initialValue" class="error-text">{{ formErrors.initialValue }}</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="dateAcquired">Date Acquired *</label>
            <input id="dateAcquired" v-model="formData.dateAcquired" type="date" />
          </div>

          <div class="form-group">
            <label for="dateDisposed">Date Disposed</label>
            <input id="dateDisposed" v-model="formData.dateDisposed" type="date" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="depGroup">Depreciation Group</label>
            <select id="depGroup" v-model.number="formData.depreciationGroup">
              <option :value="1">1 - Intangible (3-5 years)</option>
              <option :value="2">2 - Buildings (20-40 years)</option>
              <option :value="3">3 - Trucks (8 years)</option>
              <option :value="4">4 - Computers (4 years)</option>
              <option :value="5">5 - Equipment (5-8 years)</option>
              <option :value="6">6 - Furniture (5 years)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="depMethod">Depreciation Method</label>
            <select id="depMethod" v-model="formData.depreciationMethod">
              <option value="linear">Linear</option>
              <option value="accelerated">Accelerated</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="paymentMethod">Payment Method</label>
          <input id="paymentMethod" v-model="formData.paymentMethod" type="text" placeholder="e.g., Bank Transfer" />
        </div>

        <div class="form-group">
          <label>
            <input v-model="formData.taxDeductible" type="checkbox" />
            Tax Deductible
          </label>
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
