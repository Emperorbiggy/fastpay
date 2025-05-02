<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
      <!-- Header -->
      <div class="relative flex items-center justify-center px-6 py-5 bg-gradient-to-r from-[#8541f5] to-[#a75ef5] text-white shadow-lg">
        <h1 class="text-xl font-semibold">Enter Transaction PIN</h1>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center z-40">
        <div class="loader"></div>
      </div>

      <!-- PIN Input Section -->
      <div class="flex-1 flex justify-center items-center">
        <div class="w-72 p-6 bg-white rounded-xl shadow-lg space-y-6 relative">
          <!-- X icon -->
          <button @click="goBack" class="absolute top-3 right-3 text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <div class="text-center text-gray-700 text-lg font-medium mt-2">
            Enter your 4-digit PIN
          </div>

          <!-- PIN Circles -->
          <div class="flex justify-center space-x-4">
            <template v-for="i in 4" :key="i">
              <div
                class="w-5 h-5 rounded-full"
                :class="pin.length >= i ? 'bg-[#8541f5]' : 'bg-gray-300'"
              ></div>
            </template>
          </div>

          <!-- Number Pad -->
          <div class="grid grid-cols-3 gap-4 mt-8 justify-items-center">
            <template v-for="n in 9" :key="n">
              <button
                class="w-14 h-14 rounded-full bg-gray-200 text-xl font-semibold text-gray-800 hover:bg-[#8541f5] hover:text-white transition duration-200"
                @click="addPin(n)"
              >
                {{ n }}
              </button>
            </template>

            <!-- Fingerprint Icon -->
            <button
              class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-200 text-xl hover:bg-[#8541f5] hover:text-white transition duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 1.75a8.25 8.25 0 00-8.25 8.25v.75a.75.75 0 001.5 0v-.75a6.75 6.75 0 1113.5 0c0 1.014-.02 1.902-.073 2.7a.75.75 0 001.496.1c.056-.836.077-1.783.077-2.8A8.25 8.25 0 0012 1.75zm0 3a5.25 5.25 0 00-5.25 5.25v.75a.75.75 0 001.5 0v-.75a3.75 3.75 0 117.5 0c0 3.684-.251 6.685-.914 9.16a.75.75 0 001.454.38c.702-2.683.96-5.856.96-9.54A5.25 5.25 0 0012 4.75zm0 3a2.25 2.25 0 00-2.25 2.25v.75a.75.75 0 001.5 0v-.75a.75.75 0 111.5 0c0 3.12-.379 5.585-.99 7.37a.75.75 0 101.412.495c.673-1.92 1.078-4.586 1.078-7.865A2.25 2.25 0 0012 7.75zm0 3a.75.75 0 00-.75.75c0 2.34-.358 3.827-.802 4.91a.75.75 0 101.388.58c.524-1.257.914-2.955.914-5.49a.75.75 0 00-.75-.75z"/>
              </svg>
            </button>

            <!-- 0 -->
            <button
              class="w-14 h-14 rounded-full bg-gray-200 text-xl font-semibold text-gray-800 hover:bg-[#8541f5] hover:text-white transition duration-200"
              @click="addPin(0)"
            >
              0
            </button>

            <!-- Erase Icon -->
            <button
              class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-200 text-xl text-red-600 hover:bg-red-100 transition duration-200"
              @click="removePin"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                   viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Confirm -->
          <button
            :disabled="pin.length !== 4"
            @click="submitTransaction"
            class="w-full py-3 bg-[#8541f5] text-white text-lg rounded-xl font-semibold hover:bg-[#6e3ee0] transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed mt-4"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'
  import { router } from '@inertiajs/vue3'
  import axios from 'axios'

  const pin = ref('')
  const loading = ref(false) // Loading state

  const addPin = (number) => {
    if (pin.value.length < 4) {
      pin.value += number
    }
  }

  const removePin = () => {
    pin.value = pin.value.slice(0, -1)
  }

  const submitTransaction = async () => {
    if (pin.value.length !== 4) return; // Ensure pin is 4 digits

    loading.value = true; // Show loader

    try {
      // Make a request to verify the PIN
      const response = await axios.post('/api/verify-pin', { pin: pin.value }, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}` // Bearer token from local storage
        }
      });

      if (response.data.success) {
        // PIN is verified successfully, now retrieve the transfer data from local storage
        const transferData = JSON.parse(localStorage.getItem('transferData'));

        // Check if the type is 'wallet' before making the transfer request
        if (transferData && transferData.type === 'wallet') {
          const { amount, user_id, description } = transferData;

          // Send the transfer data to the /api/transfer endpoint
          const transferResponse = await axios.post('/api/transfer', {
            amount,
            user_id,
            description
          }, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}` // Bearer token from local storage
            }
          });

          // Handle transfer success response
          if (transferResponse.data.message === 'Transfer successful!') {
            // Redirect to the success page
            router.visit('/success');
          } else {
            // Handle transfer failure
            alert(transferResponse.data.message || 'Transfer failed');
          }
        }
      } else {
        alert(response.data.message || 'PIN verification failed');
      }
    } catch (error) {
      console.error('An error occurred during the transaction:', error);
      alert('An error occurred. Please try again.');
    } finally {
      loading.value = false; // Hide loader after completion
    }
  }

  const goBack = () => {
    window.history.back()
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
