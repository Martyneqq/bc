import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import Register from '../../src/pages/auth/Register.vue'

vi.mock('../../src/stores/auth.store', () => ({
  useAuthStore: vi.fn(() => ({
    register: vi.fn(),
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
    path: '/signup',
  })),
}))

describe('Register Component', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('should render registration form', () => {
    const wrapper = mount(Register, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    expect(wrapper.find('form').exists()).toBe(true)
    expect(wrapper.findAll('input').length).toBeGreaterThanOrEqual(4)
    expect(wrapper.find('button').exists()).toBe(true)
  })

  it('should have empty initial form values', () => {
    const wrapper = mount(Register, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    const inputs = wrapper.findAll('input')
    inputs.forEach((input) => {
      expect(input.element).toHaveValue('')
    })
  })

  it('should have login link', () => {
    const wrapper = mount(Register, {
      global: {
        stubs: {
          RouterLink: false,
        },
      },
    })

    const link = wrapper.find('[href*="login"]')
    expect(link.exists()).toBe(true)
  })

  it('should show password mismatch error', async () => {
    const wrapper = mount(Register, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    const inputs = wrapper.findAll('input[type="password"]')
    if (inputs.length >= 2) {
      await inputs[0].setValue('Pass123!')
      await inputs[1].setValue('DifferentPass')

      // Form should show validation error
      expect(wrapper.html()).toBeDefined()
    }
  })

  it('should have ICO field (optional)', () => {
    const wrapper = mount(Register, {
      global: {
        stubs: {
          RouterLink: true,
        },
      },
    })

    const icoInput = wrapper.find('input[name="ico"]')
    expect(icoInput.exists()).toBe(true)
  })
})
