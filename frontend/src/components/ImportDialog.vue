<script setup lang="ts">
import { ref } from 'vue'
import { exportImportAPI } from '../../api/export-import.api'

interface Props {
  isOpen?: boolean
  type?: 'income-expense' | 'assets' | 'demands-debts'
}

interface Emits {
  (e: 'close'): void
  (e: 'success', report: any): void
}

const props = withDefaults(defineProps<Props>(), {
  isOpen: false,
  type: 'income-expense',
})

const emit = defineEmits<Emits>()

const file = ref<File | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const success = ref<string | null>(null)
const dryRun = ref(false)
const importReport = ref<any>(null)

function handleFileSelect(event: Event) {
  const target = event.target as HTMLInputElement
  file.value = target.files?.[0] || null
  error.value = null
}

async function handleImport() {
  if (!file.value) {
    error.value = 'Please select a file'
    return
  }

  loading.value = true
  error.value = null
  success.value = null
  importReport.value = null

  try {
    const report = await exportImportAPI.import(file.value, props.type, dryRun.value)
    importReport.value = report

    if (dryRun.value) {
      success.value = `Validation completed: ${report.succeeded} records valid, ${report.failed} errors`
    } else {
      success.value = `Import completed: ${report.succeeded} records imported`
    }

    emit('success', report)
  } catch (err: any) {
    error.value = err.message || 'Import failed'
  } finally {
    loading.value = false
  }
}

function close() {
  file.value = null
  error.value = null
  success.value = null
  importReport.value = null
  emit('close')
}
</script>

<template>
  <div v-if="isOpen" class="modal-overlay" @click="close">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>Import {{ type }}</h2>
        <button class="close-button" @click="close">&times;</button>
      </div>

      <div class="modal-body">
        <div v-if="error" class="alert alert-error">{{ error }}</div>
        <div v-if="success" class="alert alert-success">{{ success }}</div>

        <div class="form-group">
          <label for="file-input">Select file (XLSX or CSV)</label>
          <input
            id="file-input"
            type="file"
            accept=".xlsx,.xls,.csv"
            @change="handleFileSelect"
            :disabled="loading"
          />
        </div>

        <div class="form-group">
          <label>
            <input type="checkbox" v-model="dryRun" :disabled="loading" />
            Dry run (validate without importing)
          </label>
        </div>

        <div v-if="importReport" class="report">
          <div class="report-item">
            <strong>Succeeded:</strong> {{ importReport.succeeded }}
          </div>
          <div class="report-item">
            <strong>Failed:</strong> {{ importReport.failed }}
          </div>
          <div v-if="importReport.errors.length > 0" class="report-errors">
            <p><strong>Errors:</strong></p>
            <ul>
              <li v-for="err in importReport.errors.slice(0, 5)" :key="`${err.row}-${err.error}`">
                Row {{ err.row }}: {{ err.error }}
              </li>
            </ul>
            <p v-if="importReport.errors.length > 5" class="text-muted">
              ... and {{ importReport.errors.length - 5 }} more errors
            </p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" @click="close" :disabled="loading">Cancel</button>
        <button
          class="btn btn-primary"
          @click="handleImport"
          :disabled="!file || loading"
        >
          {{ loading ? 'Processing...' : dryRun ? 'Validate' : 'Import' }}
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
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  max-width: 500px;
  width: 90%;
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

.modal-footer {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.alert {
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1rem;
}

.alert-error {
  background-color: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.alert-success {
  background-color: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #374151;
}

.form-group input[type='file'],
.form-group input[type='checkbox'] {
  font-family: inherit;
}

.form-group input[type='file'] {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.report {
  background: #f9fafb;
  padding: 1rem;
  border-radius: 6px;
  margin: 1rem 0;
}

.report-item {
  margin-bottom: 0.5rem;
}

.report-errors {
  margin-top: 1rem;
}

.report-errors ul {
  margin: 0.5rem 0 0 1.5rem;
  padding: 0;
}

.report-errors li {
  margin-bottom: 0.25rem;
  color: #6b7280;
  font-size: 0.9rem;
}

.text-muted {
  color: #9ca3af;
  font-size: 0.9rem;
  margin: 0.5rem 0 0 1.5rem;
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

.btn-primary:hover:not(:disabled) {
  background-color: #2563eb;
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
