<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import ExportButton from '../../components/ExportButton.vue'
import ImportDialog from '../../components/ImportDialog.vue'
import EditAssetModal from '../../components/EditAssetModal.vue'
import AuditLogViewer from '../../components/AuditLogViewer.vue'
import { assetAPI, type Asset, type AssetInput } from '../../api/asset'

const records = ref<Asset[]>([])
const loading = ref(false)
const showForm = ref(false)
const showDisposed = ref(false)
const showImportDialog = ref(false)
const editingRecord = ref<Asset | null>(null)
const showEditModal = ref(false)
const selectedRowId = ref<number | null>(null)
const error = ref<string | null>(null)
const success = ref<string | null>(null)

const formData = ref<AssetInput>({
  name: '',
  dateAcquired: new Date().toISOString().split('T')[0],
  initialValue: 0,
  type: 'tangible',
  depreciationGroup: 1,
  depreciationMethod: 'linear',
  taxDeductible: true,
  paymentMethod: 'bank_transfer',
  description: '',
})

const formErrors = ref<Record<string, string>>({})

const filteredRecords = computed(() => {
  return records.value.filter(record => {
    if (!showDisposed.value && record.dateDisposed) {
      return false
    }
    return true
  })
})

async function fetchRecords() {
  loading.value = true
  error.value = null
  try {
    records.value = await assetAPI.list(showDisposed.value)
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
  if (!formData.value.initialValue || formData.value.initialValue <= 0) {
    formErrors.value.initialValue = 'Value must be greater than 0'
  }

  if (Object.keys(formErrors.value).length > 0) {
    return
  }

  try {
    const dateObj = new Date(formData.value.dateAcquired)
    dateObj.setHours(12, 0, 0, 0)

    const input: AssetInput = {
      ...formData.value,
      dateAcquired: dateObj.toISOString(),
      dateDisposed: formData.value.dateDisposed
        ? new Date(formData.value.dateDisposed + 'T12:00:00Z').toISOString()
        : undefined,
    }

    await assetAPI.create(input)
    success.value = 'Asset created successfully!'

    formData.value = {
      name: '',
      dateAcquired: new Date().toISOString().split('T')[0],
      initialValue: 0,
      type: 'tangible',
      depreciationGroup: 1,
      depreciationMethod: 'linear',
      taxDeductible: true,
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
  if (!confirm('Are you sure you want to delete this asset?')) {
    return
  }

  try {
    await assetAPI.delete(id)
    success.value = 'Asset deleted successfully!'
    await fetchRecords()
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to delete record'
  }
}

function openEditModal(record: Asset) {
  editingRecord.value = record
  selectedRowId.value = record.id
  showEditModal.value = true
}

async function handleEditSave(data: Partial<AssetInput>) {
  if (!editingRecord.value) return

  try {
    const input: any = {
      ...data,
      dateAcquired: data.dateAcquired
        ? new Date(data.dateAcquired + 'T12:00:00Z').toISOString()
        : undefined,
      dateDisposed: data.dateDisposed
        ? new Date(data.dateDisposed + 'T12:00:00Z').toISOString()
        : undefined,
      initialValue: (data.initialValue || 0) * 100,
    }

    await assetAPI.update(editingRecord.value.id, input)
    success.value = 'Asset updated successfully!'
    showEditModal.value = false
    selectedRowId.value = null
    await fetchRecords()
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to update record'
  }
}

function handleImportSuccess() {
  success.value = 'Assets imported successfully!'
  showImportDialog.value = false
  fetchRecords()
  setTimeout(() => {
    success.value = null
  }, 3000)
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
      <h1>Majetek</h1>
      <div class="header-actions">
        <ExportButton 
          type="assets" 
          format="xlsx"
          label="Export"
        />
        <button class="btn btn-secondary" @click="showImportDialog = true">
          Import Data
        </button>
        <button class="btn btn-primary" @click="showForm = !showForm">
          {{ showForm ? 'Cancel' : 'Add Asset' }}
        </button>
      </div>
    </div>

    <ImportDialog 
      :is-open="showImportDialog" 
      type="assets"
      @close="showImportDialog = false"
      @success="handleImportSuccess"
    />

    <EditAssetModal
      :is-open="showEditModal"
      :record="editingRecord"
      @close="showEditModal = false"
      @save="handleEditSave"
    />

    <div v-if="error" class="alert alert-error">{{ error }}</div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <div v-if="showForm" class="form-container">
      <h2>Add New Asset</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="name">Name *</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            placeholder="Asset name"
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

        <button type="submit" class="btn btn-primary">Create Asset</button>
      </form>
    </div>

    <div class="filters">
      <label>
        <input v-model="showDisposed" type="checkbox" @change="fetchRecords" />
        Show Disposed Assets
      </label>
    </div>

    <div class="table-section">
      <h2>Assets</h2>

      <div v-if="loading" class="loading">Loading...</div>

      <div v-else-if="filteredRecords.length === 0" class="no-data">No assets found</div>

      <table v-else class="records-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Acquired</th>
            <th>Disposed</th>
            <th>Value</th>
            <th>Depreciation</th>
            <th>Tax Deductible</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in filteredRecords" :key="record.id">
            <td>{{ record.name }}</td>
            <td>
              <span class="badge">{{ record.type }}</span>
            </td>
            <td>{{ formatDate(record.dateAcquired) }}</td>
            <td>{{ record.dateDisposed ? formatDate(record.dateDisposed) : '—' }}</td>
            <td class="amount">{{ formatCurrency(record.initialValue) }}</td>
            <td>{{ record.depreciationMethod }} (Group {{ record.depreciationGroup }})</td>
            <td>{{ record.taxDeductible ? 'Yes' : 'No' }}</td>
            <td>
              <button class="btn btn-sm btn-primary" @click="openEditModal(record)">Edit</button>
              <button class="btn btn-sm btn-danger" @click="deleteRecord(record.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <AuditLogViewer
        v-if="selectedRowId"
        :entity-type="'Asset'"
        :entity-id="selectedRowId"
      />
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
  gap: 1rem;
}

.header-actions {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
  justify-content: flex-end;
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
  padding: 1rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
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
  background-color: #dbeafe;
  color: #1e40af;
}

.loading,
.no-data {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}
</style>
