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
    </div>
  </template>

  <script setup>
  import { router } from '@inertiajs/vue3'

  const goHome = () => {
    router.visit('/dashboard') // Adjust if your home route is different
  }

  const downloadReceipt = () => {
    // Example logic — replace with actual file download
    alert('Downloading receipt...')
  }
  </script>
