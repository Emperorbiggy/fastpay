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
            <img src="/images/google-icon.svg" class="h-5 w-5 mr-2" /> Google
          </button>
          <button class="social-btn ml-2">
            <img src="/images/apple-icon.svg" class="h-5 w-5 mr-2" /> Apple
          </button>
        </div>

        <p class="mt-6 text-sm">
          Don’t have an account?
          <a href="/register" class="text-[#8541f5] font-semibold">Sign up</a>
        </p>
      </div>
    </div>
  </template>

  <script setup>
  import { reactive } from 'vue'
  import { router } from '@inertiajs/vue3'

  const form = reactive({
    email: '',
    password: '',
    remember: false,
  })

  function submit() {
    router.post('/login', form)
  }
  </script>

  <style scoped>
  .input {
    @apply w-full p-3 mb-4 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#8541f5];
  }
  .social-btn {
    @apply flex items-center border w-full justify-center py-2 rounded-lg;
  }
  </style>
