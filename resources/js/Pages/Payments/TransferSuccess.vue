<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
      <!-- Header -->
      <div class="px-6 py-5 bg-gradient-to-r from-[#8541f5] to-[#a75ef5] text-white shadow-lg text-center">
        <h1 class="text-xl font-semibold">Transfer Successful</h1>
      </div>

      <!-- Content -->
      <div class="flex-1 flex flex-col justify-center items-center px-6 py-8 space-y-6">
        <!-- Success Illustration -->
        <img src="/images/success.png" alt="Success" class="w-40 h-40" />

        <!-- Message -->
        <h2 class="text-2xl font-bold text-gray-800">Transfer Completed!</h2>
        <p class="text-gray-600 text-center">Your transfer has been successfully processed.</p>
      </div>

      <!-- Action Buttons -->
      <div class="px-6 py-4 bg-white border-t shadow-lg flex justify-between gap-4">
        <button
          @click="goHome"
          class="w-1/2 py-4 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition duration-200"
        >
          Back to Home
        </button>
        <button
          @click="downloadReceipt"
          class="w-1/2 py-4 bg-[#8541f5] text-white font-semibold rounded-xl hover:bg-[#6e3ee0] transition duration-200"
        >
          Download Receipt
        </button>
      </div>
      <div v-if="loading" class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50">
        <div class="text-center">
          <div class="border-t-4 border-b-4 border-custom-purple w-16 h-16 rounded-full animate-spin mx-auto"></div>
          <p class="text-white mt-4">Please wait...</p>
        </div>
      </div>

    </div>
  </template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const loading = ref(false) // Track loader state

const goHome = () => {
  loading.value = true
  router.visit('/dashboard') // Adjust if your home route is different
}

const downloadReceipt = () => {
  // Example logic — replace with actual file download
  alert('Downloading receipt...')
}
</script>
<style scoped>
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
