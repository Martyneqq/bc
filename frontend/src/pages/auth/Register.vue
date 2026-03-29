<template>
  <div class="register-container">
    <div class="register-box">
      <h1>Tax Records</h1>
      <h2>Create Account</h2>

      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="username">Username</label>
          <input
            v-model="form.username"
            type="text"
            id="username"
            minlength="3"
            required
            :disabled="authStore.isLoading"
          />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            required
            :disabled="authStore.isLoading"
          />
        </div>

        <div class="form-group">
          <label for="ico">ICO (Czech Business ID)</label>
          <input
            v-model="form.ico"
            type="text"
            id="ico"
            placeholder="Optional"
            :disabled="authStore.isLoading"
          />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            minlength="8"
            required
            :disabled="authStore.isLoading"
          />
          <small>At least 8 characters</small>
        </div>

        <div class="form-group">
          <label for="confirm">Confirm Password</label>
          <input
            v-model="form.confirm"
            type="password"
            id="confirm"
            minlength="8"
            required
            :disabled="authStore.isLoading"
          />
        </div>

        <div v-if="authStore.error" class="error-message">{{ authStore.error }}</div>
        <div v-if="passwordMismatch" class="error-message">Passwords do not match</div>

        <button
          type="submit"
          :disabled="authStore.isLoading || passwordMismatch"
          class="btn-submit"
        >
          {{ authStore.isLoading ? 'Creating account...' : 'Register' }}
        </button>
      </form>

      <p class="login-link">
        Already have an account? <router-link to="/login">Login here</router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  username: '',
  email: '',
  ico: '',
  password: '',
  confirm: '',
})

const passwordMismatch = computed(() => form.password && form.password !== form.confirm)

const handleRegister = async () => {
  if (passwordMismatch.value) return

  const success = await authStore.register(form.username, form.email, form.password, form.ico)
  if (success) {
    router.push('/')
  }
}
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1rem;
}

.register-box {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 450px;
}

h1 {
  text-align: center;
  color: #667eea;
  margin-bottom: 0.5rem;
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
  font-weight: 500;
}

input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

input:focus {
  outline: none;
  border-color: #667eea;
}

input:disabled {
  background-color: #f5f5f5;
}

small {
  display: block;
  color: #666;
  font-size: 0.85rem;
  margin-top: 0.25rem;
}

.error-message {
  background-color: #fee;
  color: #c00;
  padding: 0.75rem;
  border-radius: 4px;
  margin-bottom: 1rem;
  font-size: 0.875rem;
}

.btn-submit {
  width: 100%;
  padding: 0.75rem;
  background-color: #667eea;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-submit:hover:not(:disabled) {
  background-color: #5568d3;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.login-link {
  text-align: center;
  margin-top: 1rem;
  color: #666;
}

.login-link a {
  color: #667eea;
  text-decoration: none;
  font-weight: 500;
}

.login-link a:hover {
  text-decoration: underline;
}
</style>
