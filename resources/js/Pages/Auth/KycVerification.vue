<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
      <div class="w-full max-w-lg bg-white rounded-lg shadow-md p-8">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="/images/logo.png" alt="Logo" class="h-12" />
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-center mb-4">KYC Verification</h2>
        <p class="text-center text-sm text-gray-500 mb-8">Upload your ID details to verify your account.</p>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
          <!-- ID Number -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
            <input v-model="form.idNumber" type="text" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Enter your ID number" required />
          </div>

          <!-- ID Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID Type</label>
            <select v-model="form.idType" class="w-full p-3 border border-gray-300 rounded-lg" required>
              <option value="" disabled>Select ID Type</option>
              <option value="NIN">NIN</option>
              <option value="BVN">BVN</option>
              <option value="VOTERS_CARD">Voters Card</option>
            </select>
          </div>

          <!-- Front Image -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Front Image</label>
            <input @change="handleFileUpload('front', $event)" type="file" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg" required />
            <div v-if="frontPreview" class="mt-2">
              <img :src="frontPreview" alt="Front Preview" class="w-full rounded-lg border" />
            </div>
          </div>

          <!-- Back Image -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Back Image</label>
            <input @change="handleFileUpload('back', $event)" type="file" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg" required />
            <div v-if="backPreview" class="mt-2">
              <img :src="backPreview" alt="Back Preview" class="w-full rounded-lg border" />
            </div>
          </div>

          <!-- Submit -->
          <button type="submit" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold">
            Verify
          </button>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { reactive, ref } from 'vue'
  import { router } from '@inertiajs/vue3'

  const form = reactive({
    idNumber: '',
    idType: '',
    frontImage: null,
    backImage: null
  })

  const frontPreview = ref(null)
  const backPreview = ref(null)

  function handleFileUpload(side, event) {
    const file = event.target.files[0]
    const previewUrl = URL.createObjectURL(file)

    if (side === 'front') {
      form.frontImage = file
      frontPreview.value = previewUrl
    } else if (side === 'back') {
      form.backImage = file
      backPreview.value = previewUrl
    }
  }

  function submitForm() {
    const formData = new FormData()
    formData.append('idNumber', form.idNumber)
    formData.append('idType', form.idType)
    formData.append('frontImage', form.frontImage)
    formData.append('backImage', form.backImage)

    router.post('/kyc-verification', formData)
  }
  </script>
