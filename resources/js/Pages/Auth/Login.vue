<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4">
      <div class="w-full max-w-md text-center">
        <img src="/images/logo.svg" alt="Logo" class="mx-auto h-12 mb-4" />
        <h2 class="text-2xl font-bold mb-1">Log in Account</h2>
        <p class="text-sm text-gray-500 mb-6">Welcome back! Select method to log in:</p>

        <form @submit.prevent="submit" class="text-left">
          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="input"
          />
          <input
            v-model="form.password"
            type="password"
            placeholder="Password"
            class="input"
          />
          <div class="flex items-center mb-4">
            <input type="checkbox" id="remember" v-model="form.remember" class="mr-2" />
            <label for="remember" class="text-sm">Remember me</label>
          </div>
          <button type="submit" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold">
            LOGIN
          </button>
          <div class="text-right mt-2 text-sm">
            <a href="#" class="text-[#8541f5]">Forgot password?</a>
          </div>
        </form>

        <div class="mt-6 flex items-center justify-between">
          <button class="social-btn mr-2">
            <img src="/images/google_icon.png" class="h-5 w-5 mr-2" /> Google
          </button>
          <button class="social-btn ml-2">
            <img src="/images/apple-icon.png" class="h-5 w-5 mr-2" /> Apple
          </button>
        </div>

        <p class="mt-6 text-sm">
          Don’t have an account?
          <a href="/register" class="text-[#8541f5] font-semibold">Sign up</a>
        </p>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50">
        <div class="text-center">
          <div class="border-t-4 border-b-4 border-custom-purple w-16 h-16 rounded-full animate-spin mx-auto"></div>
          <p class="text-white mt-4">Please wait...</p>
        </div>
      </div>

      <!-- Popup Message -->
      <div v-if="message" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-[#8541f5] text-white px-6 py-4 rounded-lg shadow-lg">
        <p>{{ message }}</p>
      </div>
    </div>
  </template>

  <script setup>
  import { reactive, ref } from 'vue'
  import { router } from '@inertiajs/vue3'  // Importing Inertia's router
  import axios from 'axios'

  const form = reactive({
    email: '',
    password: '',
    remember: false,
  })

  const loading = ref(false)
  const message = ref('')  // State for response message

  async function submit() {
    loading.value = true  // Start loading

    try {
      // Send login request to the API
      const response = await axios.post('/api/login', {
        email: form.email,
        password: form.password,
      })

      // Check if login was successful and store token
      if (response.data.token) {
        // Store the token in localStorage
        localStorage.setItem('token', response.data.token)

        // Optionally store user data if you need it
        localStorage.setItem('user', JSON.stringify(response.data.user))

        // Redirect to the dashboard using Inertia's visit method
        router.visit('/dashboard')
      } else {
        // Display message
        message.value = 'Invalid login credentials.'
        setTimeout(() => { message.value = '' }, 5000)  // Hide after 3 seconds
      }
    } catch (error) {
      console.error('Error during login:', error)
      message.value = 'Invalid login credentials.'
      setTimeout(() => { message.value = '' }, 5000)  // Hide after 3 seconds
    } finally {
      loading.value = false  // Stop loading
    }
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
