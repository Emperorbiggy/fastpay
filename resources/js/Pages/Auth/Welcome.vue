<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
      <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8 text-center">

        <!-- Header Illustration -->
        <div class="mb-6">
          <img src="/images/success-illustration.png" alt="Success" class="mx-auto h-32 w-auto" />
        </div>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-[#8541f5] mb-4">Registration Successful!</h2>

        <!-- Subtitle -->
        <p class="text-gray-600 mb-6">Welcome to <span class="font-semibold">FastPay</span></p>

        <!-- Logo -->
        <div class="flex justify-center mb-8">
          <img src="/images/logo.png" alt="App Logo" class="h-24 w-auto" />
        </div>

        <!-- Continue Button -->
        <button
          @click="goToDashboard"
          class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold transition duration-300 hover:bg-[#6c2ed1]"
        >
          Continue
        </button>
      </div>
    </div>
  </template>

  <script setup>
  import { router } from '@inertiajs/vue3'

  function goToDashboard() {
    router.visit('/dashboard')
  }
  </script>
