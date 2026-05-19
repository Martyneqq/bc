<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { auditAPI, type AuditLog } from '../../api/audit.api'

interface Props {
  entityType: string
  entityId: number
}

const props = defineProps<Props>()

const logs = ref<AuditLog[]>([])
const loading = ref(false)
const error = ref<string | null>(null)
const expandedLog = ref<number | null>(null)

async function loadLogs() {
  loading.value = true
  error.value = null

  try {
    logs.value = await auditAPI.getEntityLogs(props.entityType, props.entityId)
  } catch (err: any) {
    error.value = err.message || 'Failed to load audit logs'
  } finally {
    loading.value = false
  }
}

function toggleExpand(id: number) {
  expandedLog.value = expandedLog.value === id ? null : id
}

function formatValue(value: any): string {
  if (value === null || value === undefined) return '—'
  if (typeof value === 'boolean') return value ? 'Yes' : 'No'
  if (typeof value === 'number') return value.toString()
  if (typeof value === 'object') return JSON.stringify(value, null, 2)
  return String(value)
}

onMounted(() => {
  loadLogs()
})
</script>

<template>
  <div class="audit-logs">
    <h3>Change History</h3>

    <div v-if="error" class="alert alert-error">{{ error }}</div>

    <div v-if="loading" class="loading">Loading...</div>

    <div v-else-if="logs.length === 0" class="no-logs">No changes recorded yet</div>

    <div v-else class="logs-list">
      <div v-for="log in logs" :key="log.id" class="log-entry">
        <div class="log-header" @click="toggleExpand(log.id)">
          <div class="log-info">
            <span class="action-badge" :class="log.action.toLowerCase()">
              {{ auditAPI.formatAction(log.action) }}
            </span>
            <span class="log-date">{{ auditAPI.formatDate(log.createdAt) }}</span>
            <span class="log-user" v-if="log.user">by {{ log.user.username }}</span>
          </div>
          <div class="expand-icon">{{ expandedLog === log.id ? '▼' : '▶' }}</div>
        </div>

        <div v-if="expandedLog === log.id" class="log-details">
          <div v-if="log.changes?.changed" class="changes">
            <div v-for="(change, field) in log.changes.changed" :key="field" class="change-item">
              <div class="field-name">{{ field }}:</div>
              <div class="change-values">
                <div v-if="change.before !== undefined" class="value before">
                  <span class="label">Before:</span>
                  <span class="text">{{ formatValue(change.before) }}</span>
                </div>
                <div v-if="change.after !== undefined" class="value after">
                  <span class="label">After:</span>
                  <span class="text">{{ formatValue(change.after) }}</span>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="log.changes?.before || log.changes?.after" class="full-record">
            <div v-if="log.changes.before" class="record-view">
              <div class="record-title">Deleted Record:</div>
              <pre>{{ JSON.stringify(log.changes.before, null, 2) }}</pre>
            </div>
            <div v-if="log.changes.after" class="record-view">
              <div class="record-title">Created Record:</div>
              <pre>{{ JSON.stringify(log.changes.after, null, 2) }}</pre>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.audit-logs {
  margin-top: 2rem;
  padding: 1.5rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h3 {
  margin: 0 0 1rem 0;
  font-size: 1.1rem;
  color: #1f2937;
}

.alert {
  padding: 0.75rem;
  border-radius: 6px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.alert-error {
  background-color: #fee2e2;
  border: 1px solid #fecaca;
  color: #991b1b;
}

.loading,
.no-logs {
  padding: 1rem;
  text-align: center;
  color: #6b7280;
}

.logs-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.log-entry {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  overflow: hidden;
}

.log-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: #f9fafb;
  cursor: pointer;
  user-select: none;
  transition: background 0.2s;
}

.log-header:hover {
  background: #f3f4f6;
}

.log-info {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  flex: 1;
}

.action-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 4px;
  font-size: 0.8rem;
  font-weight: 600;
  color: white;
  min-width: 80px;
  text-align: center;
}

.action-badge.create {
  background-color: #10b981;
}

.action-badge.update {
  background-color: #f59e0b;
}

.action-badge.delete {
  background-color: #ef4444;
}

.log-date {
  font-size: 0.9rem;
  color: #6b7280;
  min-width: 180px;
}

.log-user {
  font-size: 0.9rem;
  color: #6b7280;
}

.expand-icon {
  margin-left: auto;
  color: #9ca3af;
  font-size: 0.8rem;
  transition: transform 0.2s;
}

.log-details {
  padding: 1rem;
  background: white;
  border-top: 1px solid #e5e7eb;
}

.changes {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.change-item {
  padding: 0.75rem;
  background: #f9fafb;
  border-radius: 4px;
  border-left: 3px solid #3b82f6;
}

.field-name {
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.change-values {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 0.5rem;
}

.value {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.value.before {
  opacity: 0.7;
}

.label {
  font-weight: 500;
  font-size: 0.8rem;
  color: #6b7280;
}

.text {
  font-family: 'Monaco', 'Courier New', monospace;
  font-size: 0.85rem;
  color: #1f2937;
  word-break: break-all;
}

.full-record {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.record-view {
  background: #f9fafb;
  border-radius: 4px;
  padding: 0.75rem;
}

.record-title {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #374151;
  font-size: 0.9rem;
}

pre {
  margin: 0;
  overflow-x: auto;
  font-size: 0.8rem;
  line-height: 1.4;
  color: #1f2937;
}
</style>
