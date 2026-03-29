<script setup lang="ts">
import { ref } from 'vue'
import { exportImportAPI } from '../../api/export-import.api'

interface Props {
  type?: 'income-expense' | 'assets' | 'demands-debts' | 'all'
  format?: 'xlsx' | 'csv'
  filters?: Record<string, any>
  label?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'all',
  format: 'xlsx',
  label: 'Export',
})

const loading = ref(false)
const error = ref<string | null>(null)

async function handleExport() {
  loading.value = true
  error.value = null

  try {
    const blob = await exportImportAPI.export(props.type, props.format, props.filters)
    const filename = `tax-records-${props.type}-${new Date().toISOString().split('T')[0]}.${props.format}`
    exportImportAPI.downloadExport(blob, filename)
  } catch (err: any) {
    error.value = err.message || 'Export failed'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="export-button">
    <button class="btn btn-primary" @click="handleExport" :disabled="loading">
      {{ loading ? 'Exporting...' : label }}
    </button>
    <div v-if="error" class="error-message">{{ error }}</div>
  </div>
</template>

<style scoped>
.export-button {
  display: inline-block;
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

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-message {
  color: #ef4444;
  font-size: 0.9rem;
  margin-top: 0.5rem;
}
</style>
