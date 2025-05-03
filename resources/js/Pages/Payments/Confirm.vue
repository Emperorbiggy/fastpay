<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
      <!-- Header -->
      <div class="relative flex items-center justify-center px-6 py-5 bg-gradient-to-r from-[#8541f5] to-[#a75ef5] text-white shadow-lg">
        <button @click="goBack" class="absolute left-4 text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>
        <h1 class="text-xl font-semibold">Confirm Transfer</h1>
      </div>

      <!-- Loader -->
      <div
        v-if="loading"
        class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50"
      >
      <div v-if="loading" class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50">
        <div class="text-center">
          <div class="border-t-4 border-b-4 border-custom-purple w-16 h-16 rounded-full animate-spin mx-auto"></div>
          <p class="text-white mt-4">Please wait...</p>
        </div>
      </div>
    </div>

      <!-- Scrollable Content -->
      <div class="flex-1 overflow-y-auto px-6 py-8 space-y-8">
        <!-- Transfer Illustration -->
        <div class="flex justify-center">
          <img src="/images/transfer.png" alt="Transfer" class="w-40 h-40" />
        </div>

        <!-- Transfer Details Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 space-y-4 border border-gray-200">
          <div class="flex justify-between">
            <span class="text-gray-600">Amount</span>
            <span class="font-semibold text-gray-900 text-lg">₦{{ transferData.amount }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">To</span>
            <span class="font-semibold text-gray-900">{{ transferData.account_name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Bank</span>
            <span class="text-gray-700">{{ transferData.bank }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Description</span>
            <span class="text-gray-700">{{ transferData.description || '-' }}</span>
          </div>
        </div>
      </div>

      <!-- Confirm Button -->
      <div class="px-6 py-4 bg-white border-t shadow-lg">
        <button
          class="w-full py-4 bg-[#8541f5] text-white text-lg rounded-xl font-semibold hover:bg-[#6e3ee0] transition duration-300"
          @click="goToPinPage"
        >
          Confirm
        </button>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'
  import { router } from '@inertiajs/vue3'

  // Get data from localStorage
  const transferData = JSON.parse(localStorage.getItem('transferData'))
  const loading = ref(false)

  const goBack = () => {
    window.history.back()
  }

  const goToPinPage = () => {
    loading.value = true
    setTimeout(() => {
      router.visit('/confirm-pin')
    }, 500)
  }
  </script>

  <style scoped>
  body {
    font-family: 'Inter', sans-serif;
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
