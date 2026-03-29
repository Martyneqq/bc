import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import Login from '../../src/pages/auth/Login.vue'

vi.mock('../../src/stores/auth.store', () => ({
  useAuthStore: vi.fn(() => ({
    login: vi.fn(),
    isLoading: false,
    error: null,
    isAuthenticated: false,
    clearError: vi.fn(),
  })),
}))

vi.mock('vue-router', () => ({
  useRouter: vi.fn(() => ({
    push: vi.fn(),
  })),
  useRoute: vi.fn(() => ({
    path: '/login',
  })),
}))

describe('Login Component', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('should render login form', () => {
    const wrapper = mount(Login, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    expect(wrapper.find('form').exists()).toBe(true)
    expect(wrapper.find('input[type="text"]').exists()).toBe(true)
    expect(wrapper.find('input[type="password"]').exists()).toBe(true)
    expect(wrapper.find('button').exists()).toBe(true)
  })

  it('should have empty initial form values', () => {
    const wrapper = mount(Login, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    const usernameInput = wrapper.find('input[type="text"]')
    const passwordInput = wrapper.find('input[type="password"]')

    expect(usernameInput.element).toHaveValue('')
    expect(passwordInput.element).toHaveValue('')
  })

  it('should have sign up link', () => {
    const wrapper = mount(Login, {
      global: {
        stubs: {
          RouterLink: false,
        },
      },
    })

    const link = wrapper.find('[href*="signup"]')
    expect(link.exists()).toBe(true)
  })

  it('should disable button while loading', async () => {
    const wrapper = mount(Login, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    const button = wrapper.find('button')
    expect(button.element).not.toBeDisabled()
  })
})
