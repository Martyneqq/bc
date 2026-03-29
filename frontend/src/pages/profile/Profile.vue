<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth.store'

const authStore = useAuthStore()

const userData = ref({
  username: authStore.user?.username || 'Not available',
  email: authStore.user?.email || 'Not available',
  ico: authStore.user?.ico || '',
  createdAt: authStore.user?.createdAt || new Date().toISOString(),
})

const isEditing = ref(false)
const editData = ref({ ...userData.value })
const loading = ref(false)
const error = ref<string | null>(null)
const success = ref<string | null>(null)
const changePasswordForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
})
const passwordErrors = ref<Record<string, string>>({})
const showPasswordForm = ref(false)

function startEdit() {
  isEditing.value = true
  editData.value = { ...userData.value }
  error.value = null
}

function cancelEdit() {
  isEditing.value = false
  editData.value = { ...userData.value }
  error.value = null
}

async function saveChanges() {
  error.value = null
  success.value = null

  if (!editData.value.email.trim()) {
    error.value = 'Email is required'
    return
  }

  try {
    loading.value = true
    // Here you would call an API endpoint to update user profile
    // For now, we just update the local data
    userData.value = { ...editData.value }
    success.value = 'Profile updated successfully!'
    isEditing.value = false

    setTimeout(() => {
      success.value = null
    }, 2000)
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to update profile'
  } finally {
    loading.value = false
  }
}

async function handlePasswordChange() {
  passwordErrors.value = {}
  error.value = null
  success.value = null

  if (!changePasswordForm.value.currentPassword) {
    passwordErrors.value.currentPassword = 'Current password is required'
  }

  if (!changePasswordForm.value.newPassword) {
    passwordErrors.value.newPassword = 'New password is required'
  } else if (changePasswordForm.value.newPassword.length < 6) {
    passwordErrors.value.newPassword = 'Password must be at least 6 characters'
  }

  if (changePasswordForm.value.newPassword !== changePasswordForm.value.confirmPassword) {
    passwordErrors.value.confirmPassword = 'Passwords do not match'
  }

  if (Object.keys(passwordErrors.value).length > 0) {
    return
  }

  try {
    loading.value = true
    // Here you would call an API endpoint to change password
    // For now, just show success
    success.value = 'Password changed successfully!'
    showPasswordForm.value = false
    changePasswordForm.value = {
      currentPassword: '',
      newPassword: '',
      confirmPassword: '',
    }

    setTimeout(() => {
      success.value = null
    }, 2000)
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to change password'
  } finally {
    loading.value = false
  }
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

onMounted(() => {
  // Load user data from auth store or API
  if (authStore.user) {
    userData.value = {
      username: authStore.user.username || 'Not available',
      email: authStore.user.email || 'Not available',
      ico: authStore.user.ico || '',
      createdAt: authStore.user.createdAt || new Date().toISOString(),
    }
  }
})
</script>

<template>
  <div class="profile-container">
    <h1>Profile</h1>

    <div v-if="error" class="alert alert-error">{{ error }}</div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <div class="profile-grid">
      <!-- Profile Information Section -->
      <div class="profile-card">
        <div class="card-header">
          <h2>Profile Information</h2>
          <div>
            <button v-if="!isEditing" class="btn btn-primary" @click="startEdit">
              Edit Profile
            </button>
            <div v-else class="button-group">
              <button class="btn btn-success" @click="saveChanges" :disabled="loading">
                Save Changes
              </button>
              <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
            </div>
          </div>
        </div>

        <div class="card-content">
          <div v-if="!isEditing" class="info-display">
            <div class="info-row">
              <label>Username:</label>
              <span>{{ userData.username }}</span>
            </div>
            <div class="info-row">
              <label>Email:</label>
              <span>{{ userData.email }}</span>
            </div>
            <div class="info-row">
              <label>Tax ID (IČO):</label>
              <span>{{ userData.ico || 'Not set' }}</span>
            </div>
            <div class="info-row">
              <label>Account Created:</label>
              <span>{{ formatDate(userData.createdAt) }}</span>
            </div>
          </div>

          <form v-else class="profile-form" @submit.prevent="saveChanges">
            <div class="form-group">
              <label for="username">Username</label>
              <input
                id="username"
                v-model="editData.username"
                type="text"
                disabled
                title="Username cannot be changed"
              />
              <small class="help-text">Username cannot be changed</small>
            </div>

            <div class="form-group">
              <label for="email">Email *</label>
              <input
                id="email"
                v-model="editData.email"
                type="email"
                required
                placeholder="Enter your email"
              />
            </div>

            <div class="form-group">
              <label for="ico">Tax ID (IČO)</label>
              <input
                id="ico"
                v-model="editData.ico"
                type="text"
                placeholder="Enter your tax ID (optional)"
              />
              <small class="help-text">Your Czech tax identification number</small>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Section -->
      <div class="profile-card">
        <div class="card-header">
          <h2>Security</h2>
        </div>

        <div class="card-content">
          <div v-if="!showPasswordForm" class="security-info">
            <p>Manage your account security settings</p>
            <button class="btn btn-primary" @click="showPasswordForm = true">
              Change Password
            </button>
          </div>

          <form v-else class="password-form" @submit.prevent="handlePasswordChange">
            <div class="form-group">
              <label for="currentPassword">Current Password *</label>
              <input
                id="currentPassword"
                v-model="changePasswordForm.currentPassword"
                type="password"
                :class="{ 'input-error': passwordErrors.currentPassword }"
              />
              <span v-if="passwordErrors.currentPassword" class="error-text">
                {{ passwordErrors.currentPassword }}
              </span>
            </div>

            <div class="form-group">
              <label for="newPassword">New Password *</label>
              <input
                id="newPassword"
                v-model="changePasswordForm.newPassword"
                type="password"
                :class="{ 'input-error': passwordErrors.newPassword }"
              />
              <span v-if="passwordErrors.newPassword" class="error-text">
                {{ passwordErrors.newPassword }}
              </span>
              <small class="help-text">At least 6 characters</small>
            </div>

            <div class="form-group">
              <label for="confirmPassword">Confirm New Password *</label>
              <input
                id="confirmPassword"
                v-model="changePasswordForm.confirmPassword"
                type="password"
                :class="{ 'input-error': passwordErrors.confirmPassword }"
              />
              <span v-if="passwordErrors.confirmPassword" class="error-text">
                {{ passwordErrors.confirmPassword }}
              </span>
            </div>

            <div class="button-group">
              <button type="submit" class="btn btn-success" :disabled="loading">
                Change Password
              </button>
              <button type="button" class="btn btn-secondary" @click="showPasswordForm = false">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Account Information Section -->
      <div class="profile-card">
        <div class="card-header">
          <h2>Account Information</h2>
        </div>

        <div class="card-content">
          <div class="info-display">
            <div class="info-row">
              <label>Member Since:</label>
              <span>{{ formatDate(userData.createdAt) }}</span>
            </div>
            <div class="info-row">
              <label>Account Status:</label>
              <span class="status-badge active">Active</span>
            </div>
            <div class="info-row">
              <label>Application Version:</label>
              <span>v2.0</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.profile-container {
  max-width: 1000px;
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
  margin: 0;
}

.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
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

.profile-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.profile-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 2px solid #e5e7eb;
  gap: 1rem;
}

.card-content {
  padding: 1.5rem;
}

.info-display {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #e5e7eb;
}

.info-row label {
  font-weight: 600;
  color: #6b7280;
  min-width: 150px;
}

.info-row span {
  color: #1f2937;
  text-align: right;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 500;
}

.status-badge.active {
  background-color: #dcfce7;
  color: #166534;
}

.profile-form,
.password-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-weight: 600;
  color: #374151;
  font-size: 0.95rem;
}

input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.95rem;
  font-family: inherit;
}

input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input:disabled {
  background-color: #f3f4f6;
  cursor: not-allowed;
}

input.input-error {
  border-color: #ef4444;
}

.error-text {
  font-size: 0.85rem;
  color: #ef4444;
}

.help-text {
  font-size: 0.85rem;
  color: #6b7280;
}

.button-group {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
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

.btn-success {
  background-color: #10b981;
  color: white;
}

.btn-success:hover {
  background-color: #059669;
}

.btn-success:disabled {
  background-color: #d1d5db;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #6b7280;
  color: white;
}

.btn-secondary:hover {
  background-color: #4b5563;
}

.security-info {
  text-align: center;
  padding: 1rem 0;
}

.security-info p {
  color: #6b7280;
  margin-bottom: 1rem;
}
</style>
