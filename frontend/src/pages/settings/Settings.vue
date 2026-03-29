<script setup lang="ts">
import { ref, onMounted } from 'vue'

const darkMode = ref(false)
const language = ref('en')
const success = ref<string | null>(null)

const languages = [
  { code: 'en', name: 'English' },
  { code: 'cs', name: 'Čeština' },
]

function toggleDarkMode() {
  darkMode.value = !darkMode.value
  localStorage.setItem('darkMode', JSON.stringify(darkMode.value))
  applyDarkMode()
  success.value = 'Dark mode preference saved!'
  setTimeout(() => {
    success.value = null
  }, 2000)
}

function changeLanguage() {
  localStorage.setItem('language', language.value)
  success.value = 'Language preference saved!'
  setTimeout(() => {
    success.value = null
  }, 2000)
}

function applyDarkMode() {
  if (darkMode.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

function exportData() {
  const dataToExport = {
    exportDate: new Date().toISOString(),
    darkMode: darkMode.value,
    language: language.value,
  }

  const dataStr = JSON.stringify(dataToExport, null, 2)
  const dataBlob = new Blob([dataStr], { type: 'application/json' })
  const url = URL.createObjectURL(dataBlob)
  const link = document.createElement('a')
  link.href = url
  link.download = `tax-records-settings-${new Date().toISOString().split('T')[0]}.json`
  link.click()
  URL.revokeObjectURL(url)

  success.value = 'Settings exported!'
  setTimeout(() => {
    success.value = null
  }, 2000)
}

function importData(event: Event) {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = (e) => {
    try {
      const data = JSON.parse(e.target?.result as string)
      if (data.darkMode !== undefined) {
        darkMode.value = data.darkMode
        localStorage.setItem('darkMode', JSON.stringify(darkMode.value))
        applyDarkMode()
      }
      if (data.language) {
        language.value = data.language
        localStorage.setItem('language', data.language)
      }
      success.value = 'Settings imported successfully!'
      setTimeout(() => {
        success.value = null
      }, 2000)
    } catch (err) {
      success.value = 'Error importing settings'
    }
  }
  reader.readAsText(file)
  target.value = '' // Reset file input
}

function clearAllData() {
  if (
    confirm(
      'Are you sure you want to clear all preferences? This will reset all settings to defaults.',
    )
  ) {
    localStorage.removeItem('darkMode')
    localStorage.removeItem('language')
    darkMode.value = false
    language.value = 'en'
    applyDarkMode()
    success.value = 'All preferences cleared!'
    setTimeout(() => {
      success.value = null
    }, 2000)
  }
}

onMounted(() => {
  // Load preferences from localStorage
  const savedDarkMode = localStorage.getItem('darkMode')
  if (savedDarkMode !== null) {
    darkMode.value = JSON.parse(savedDarkMode)
  }

  const savedLanguage = localStorage.getItem('language')
  if (savedLanguage) {
    language.value = savedLanguage
  }

  // Apply dark mode on load
  applyDarkMode()
})
</script>

<template>
  <div class="page-container">
    <h1>Settings</h1>

    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <div class="settings-section">
      <h2>Appearance</h2>

      <div class="setting-item">
        <div class="setting-header">
          <div>
            <h3>Dark Mode</h3>
            <p class="description">Enable dark theme for easier viewing in low light conditions</p>
          </div>
          <div class="toggle-switch">
            <input
              :id="'darkModeToggle'"
              type="checkbox"
              :checked="darkMode"
              @change="toggleDarkMode"
              class="toggle-input"
            />
            <label :for="'darkModeToggle'" class="toggle-label"></label>
          </div>
        </div>
      </div>

      <div class="setting-item">
        <div class="setting-header">
          <div>
            <h3>Language</h3>
            <p class="description">Choose your preferred language</p>
          </div>
          <select v-model="language" @change="changeLanguage" class="language-select">
            <option v-for="lang in languages" :key="lang.code" :value="lang.code">
              {{ lang.name }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="settings-section">
      <h2>Data Management</h2>

      <div class="setting-item">
        <div class="setting-header">
          <div>
            <h3>Export Settings</h3>
            <p class="description">Download your preferences as a JSON file for backup or transfer</p>
          </div>
          <button class="btn btn-secondary" @click="exportData">Export</button>
        </div>
      </div>

      <div class="setting-item">
        <div class="setting-header">
          <div>
            <h3>Import Settings</h3>
            <p class="description">Restore your preferences from a previously exported file</p>
          </div>
          <label class="btn btn-secondary" for="import-settings-input">Import</label>
          <input
            id="import-settings-input"
            type="file"
            accept=".json"
            @change="importData"
            style="display: none"
          />
        </div>
      </div>

      <div class="setting-item">
        <div class="setting-header">
          <div>
            <h3>Clear Preferences</h3>
            <p class="description">Reset all settings to their default values</p>
          </div>
          <button class="btn btn-danger" @click="clearAllData">Clear</button>
        </div>
      </div>
    </div>

    <div class="settings-section">
      <h2>Information</h2>

      <div class="info-item">
        <h3>Application Version</h3>
        <p>Tax Records v2.0</p>
      </div>

      <div class="info-item">
        <h3>Last Updated</h3>
        <p>{{ new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

h1 {
  font-size: 2rem;
  color: #1f2937;
  margin-bottom: 2rem;
}

h2 {
  font-size: 1.25rem;
  color: #374151;
  margin-top: 2rem;
  margin-bottom: 1rem;
  border-bottom: 2px solid #e5e7eb;
  padding-bottom: 0.75rem;
}

h3 {
  font-size: 1rem;
  color: #1f2937;
  margin: 0 0 0.25rem 0;
}

p {
  margin: 0;
}

.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
}

.alert-success {
  background-color: #dcfce7;
  border: 1px solid #bbf7d0;
  color: #166534;
}

.settings-section {
  margin-bottom: 2rem;
}

.setting-item {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 1rem;
}

.setting-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.description {
  font-size: 0.9rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

/* Toggle Switch */
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 30px;
}

.toggle-input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-label {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.3s;
  border-radius: 30px;
}

.toggle-label:before {
  position: absolute;
  content: '';
  height: 24px;
  width: 24px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

input:checked + .toggle-label {
  background-color: #3b82f6;
}

input:checked + .toggle-label:before {
  transform: translateX(30px);
}

.language-select {
  padding: 0.5rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.95rem;
  background-color: white;
  cursor: pointer;
}

.language-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

.btn-secondary {
  background-color: #6b7280;
  color: white;
}

.btn-secondary:hover {
  background-color: #4b5563;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
}

.btn-danger:hover {
  background-color: #dc2626;
}

.info-item {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 1rem;
}

.info-item h3 {
  margin-bottom: 0.5rem;
}
</style>
