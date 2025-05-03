<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 relative">
      <!-- Main Content -->
      <div class="w-full max-w-md text-center">
        <img src="/images/logo.svg" alt="Logo" class="mx-auto h-12 mb-4" />
        <h2 class="text-2xl font-bold mb-1">Sign Up in Account</h2>
        <p class="text-sm text-gray-500 mb-6">Join us by filling the form below</p>

        <form @submit.prevent="submit" class="text-left">
          <input v-model="form.name" type="text" placeholder="Full Name" class="input" />
          <input v-model="form.username" type="text" placeholder="Username" class="input" />
          <input v-model="form.email" type="email" placeholder="Email" class="input" />
          <input v-model="form.phone" type="tel" placeholder="Phone Number" class="input" />
          <input v-model="form.password" type="password" placeholder="Password" class="input" />
          <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="input" />

          <button
            type="submit"
            class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold"
            :disabled="loading"
          >
            Continue
          </button>
        </form>

        <!-- Popup Message -->
        <div
          v-if="popup.show"
          :class="[
            'absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-6 rounded-lg text-lg text-center',
            popup.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
          ]"
          class="z-50"
        >
          {{ popup.message }}
        </div>

        <!-- Social Buttons -->
        <div class="mt-6 flex items-center justify-between">
          <button class="social-btn mr-2">
            <img src="/images/google_icon.png" class="h-5 w-5 mr-2" /> Google
          </button>
          <button class="social-btn ml-2">
            <img src="/images/apple-icon.png" class="h-5 w-5 mr-2" /> Apple
          </button>
        </div>

        <p class="mt-6 text-sm">
          Already have an account?
          <a href="/" class="text-[#8541f5] font-semibold">Log in</a>
        </p>
      </div>

      <!-- Full-screen loader -->
      <div v-if="loading" class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50">
        <div class="text-center">
          <div class="border-t-4 border-b-4 border-custom-purple w-16 h-16 rounded-full animate-spin mx-auto"></div>
          <p class="text-white mt-4">Please wait...</p>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const form = reactive({
  name: '',
  username: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
})

const loading = ref(false)
const popup = reactive({
  show: false,
  message: '',
  type: 'success',
})

function submit() {
  loading.value = true
  popup.show = false

  fetch('/api/register', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify(form),
  })
    .then(res => {
      if (!res.ok) {
        return res.json().then(err => Promise.reject(err))
      }
      return res.json()
    })
    .then(data => {
      console.log('Registration success:', data)

      localStorage.setItem('token', data.user.token)

      popup.message = data.message || 'Registration successful!'
      popup.type = 'success'
      popup.show = true

      Object.keys(form).forEach(k => form[k] = '')

      setTimeout(() => {
        Inertia.visit('/otp')
      }, 10000)
    })
    .catch(err => {
      console.error('Registration failed:', err)

      popup.message = err.message || 'Something went wrong. Please try again.'

      // Handle validation errors (e.g., Laravel-style errors)
      if (err.errors) {
        popup.message = Object.values(err.errors).flat().join(' ')
      }

      popup.type = 'error'
      popup.show = true
    })
    .finally(() => {
      loading.value = false
      setTimeout(() => {
        popup.show = false
      }, 10000)
    })
}

</script>



  <style scoped>
  .input {
    @apply w-full p-3 mb-4 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#8541f5];
  }
  .social-btn {
    @apply flex items-center border w-full justify-center py-2 rounded-lg;
  }

  .loader {
    width: 50px;
    height: 50px;
    border: 4px solid #8541f5;
    border-top: 4px solid transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  </style>
