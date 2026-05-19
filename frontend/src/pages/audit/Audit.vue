<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { auditAPI, type AuditLog } from '../../api/audit.api'

const logs = ref<AuditLog[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const filterAction = ref<string>('')
const filterEntityType = ref<string>('')
const filterStartDate = ref<string>(new Date(new Date().getFullYear(), 0, 1).toISOString().split('T')[0])
const filterEndDate = ref<string>(new Date().toISOString().split('T')[0])

const page = ref(1)
const pageSize = ref(50)
const total = ref(0)

const expandedLog = ref<number | null>(null)

const actionOptions = ['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'EXPORT', 'IMPORT']
const entityTypes = computed(() => [...new Set(logs.value.map(l => l.entityType))])

async function loadLogs() {
  loading.value = true
  error.value = null

  try {
    const result = await auditAPI.getUserLogs({
      action: filterAction.value || undefined,
      entityType: filterEntityType.value || undefined,
      startDate: filterStartDate.value ? new Date(filterStartDate.value) : undefined,
      endDate: filterEndDate.value ? new Date(filterEndDate.value) : undefined,
      limit: pageSize.value,
      offset: (page.value - 1) * pageSize.value,
    })

    logs.value = result.logs
    total.value = result.total
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

const totalPages = computed(() => Math.ceil(total.value / pageSize.value))

onMounted(() => {
  loadLogs()
})
</script>

<template>
  <div class="audit-page">
    <h1>Audit Log</h1>
    <p class="subtitle">Complete history of all changes to your records</p>

    <!-- Filters -->
    <div class="filters">
      <div class="filter-group">
        <label for="action">Action</label>
        <select id="action" v-model="filterAction" @change="loadLogs">
          <option value="">All Actions</option>
          <option v-for="action in actionOptions" :key="action" :value="action">
            {{ auditAPI.formatAction(action) }}
          </option>
        </select>
      </div>

      <div class="filter-group">
        <label for="entityType">Entity Type</label>
        <select id="entityType" v-model="filterEntityType" @change="loadLogs">
          <option value="">All Types</option>
          <option v-for="type in entityTypes" :key="type" :value="type">
            {{ type }}
          </option>
        </select>
      </div>

      <div class="filter-group">
        <label for="startDate">From</label>
        <input id="startDate" v-model="filterStartDate" type="date" @change="loadLogs" />
      </div>

      <div class="filter-group">
        <label for="endDate">To</label>
        <input id="endDate" v-model="filterEndDate" type="date" @change="loadLogs" />
      </div>
    </div>

    <!-- Error -->
    <div v-if="error" class="alert alert-error">{{ error }}</div>

    <!-- Loading -->
    <div v-if="loading" class="loading">Loading audit logs...</div>

    <!-- Empty state -->
    <div v-else-if="logs.length === 0" class="empty-state">
      <p>No audit logs found matching your filters</p>
    </div>

    <!-- Logs -->
    <div v-else class="logs-section">
      <div class="logs-info">
        Showing {{ (page - 1) * pageSize + 1 }} to {{ Math.min(page * pageSize, total) }} of
        {{ total }} records
      </div>

      <div class="logs-list">
        <div v-for="log in logs" :key="log.id" class="log-entry">
          <div class="log-header" @click="toggleExpand(log.id)">
            <div class="header-content">
              <span class="action-badge" :class="log.action.toLowerCase()">
                {{ auditAPI.formatAction(log.action) }}
              </span>
              <span class="entity-type">{{ log.entityType }}</span>
              <span v-if="log.entityId" class="entity-id">#{{ log.entityId }}</span>
              <span class="log-date">{{ auditAPI.formatDate(log.createdAt) }}</span>
              <span class="log-user" v-if="log.user">{{ log.user.username }}</span>
            </div>
            <div class="expand-icon">{{ expandedLog === log.id ? '▼' : '▶' }}</div>
          </div>

          <div v-if="expandedLog === log.id" class="log-details">
            <div v-if="log.changes?.changed" class="changes">
              <div v-for="(change, field) in log.changes.changed" :key="field" class="change-item">
                <div class="field-name">{{ field }}</div>
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

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="pagination">
        <button
          :disabled="page === 1"
          @click="page = 1; loadLogs()"
          class="page-btn"
        >
          ⟨⟨
        </button>
        <button
          :disabled="page === 1"
          @click="page--; loadLogs()"
          class="page-btn"
        >
          ⟨
        </button>

        <div class="page-info">
          Page {{ page }} of {{ totalPages }}
        </div>

        <button
          :disabled="page === totalPages"
          @click="page++; loadLogs()"
          class="page-btn"
        >
          ⟩
        </button>
        <button
          :disabled="page === totalPages"
          @click="page = totalPages; loadLogs()"
          class="page-btn"
        >
          ⟩⟩
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.audit-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

h1 {
  font-size: 2rem;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.subtitle {
  color: #6b7280;
  margin-bottom: 2rem;
}

.filters {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filter-group label {
  font-weight: 500;
  font-size: 0.9rem;
  color: #374151;
}

.filter-group select,
.filter-group input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.9rem;
}

.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
}

.alert-error {
  background-color: #fee2e2;
  border: 1px solid #fecaca;
  color: #991b1b;
}

.loading,
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6b7280;
}

.logs-section {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.logs-info {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  font-size: 0.9rem;
  color: #6b7280;
}

.logs-list {
  divide-y: 1px solid #e5e7eb;
}

.log-entry {
  border-bottom: 1px solid #e5e7eb;
}

.log-entry:last-child {
  border-bottom: none;
}

.log-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  cursor: pointer;
  user-select: none;
  transition: background 0.2s;
}

.log-header:hover {
  background: #f9fafb;
}

.header-content {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  flex: 1;
  flex-wrap: wrap;
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

.action-badge.login {
  background-color: #3b82f6;
}

.action-badge.export {
  background-color: #8b5cf6;
}

.action-badge.import {
  background-color: #06b6d4;
}

.entity-type {
  font-weight: 600;
  color: #374151;
}

.entity-id {
  color: #9ca3af;
}

.log-date {
  font-size: 0.85rem;
  color: #6b7280;
  margin-left: auto;
}

.log-user {
  font-size: 0.85rem;
  color: #6b7280;
}

.expand-icon {
  margin-left: 1rem;
  color: #9ca3af;
}

.log-details {
  padding: 1rem 1.5rem;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.changes {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.change-item {
  padding: 0.75rem;
  background: white;
  border-radius: 4px;
  border-left: 3px solid #3b82f6;
}

.field-name {
  font-weight: 600;
  color: #374151;
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
  background: white;
  border-radius: 4px;
  padding: 1rem;
}

.record-title {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #374151;
}

pre {
  margin: 0;
  overflow-x: auto;
  font-size: 0.8rem;
  line-height: 1.4;
  color: #1f2937;
  max-height: 200px;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.page-btn {
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
  background: #f3f4f6;
  border-color: #9ca3af;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  min-width: 120px;
  text-align: center;
  font-size: 0.9rem;
  color: #6b7280;
}
</style>
