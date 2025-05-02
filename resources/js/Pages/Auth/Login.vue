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
      <div v-if="loading" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center z-40">
        <div class="loader"></div>
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
        localStorage.setItem('auth_token', response.data.token)

        // Optionally store user data if you need it
        localStorage.setItem('user', JSON.stringify(response.data.user))

        // Redirect to the dashboard using Inertia's visit method
        router.visit('/dashboard')
      } else {
        // Handle failed login (optional)
        alert('Invalid login credentials')
      }
    } catch (error) {
      console.error('Error during login:', error)
      alert('An error occurred. Please try again later.')
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
