<template>
    <div class="min-h-screen bg-white px-4 flex items-center justify-center">
      <!-- Loading Spinner -->
      <div v-if="loading" class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50">
        <div class="text-center">
          <div class="border-t-4 border-b-4 border-custom-purple w-16 h-16 rounded-full animate-spin mx-auto"></div>
          <p class="text-white mt-4">Please wait...</p>
        </div>
      </div>

      <div class="w-full max-w-md text-center">
        <!-- User verification icon and background -->
        <div class="p-5 rounded-full inline-block mb-6">
          <img src="/images/verification.png" alt="Verification Icon" class="h-16 w-16 mx-auto" />
        </div>

        <!-- OTP Verification Header -->
        <h2 class="text-2xl font-bold mb-2">Verify Your Phone Number</h2>
        <p class="text-sm text-gray-500 mb-4">Enter the OTP sent to your phone</p>

        <!-- OTP Input fields -->
        <div class="flex justify-center space-x-4 mb-4">
          <input v-model="otp[0]" type="text" maxlength="1" class="otp-input" />
          <input v-model="otp[1]" type="text" maxlength="1" class="otp-input" />
          <input v-model="otp[2]" type="text" maxlength="1" class="otp-input" />
          <input v-model="otp[3]" type="text" maxlength="1" class="otp-input" />
        </div>

        <!-- Email Address Display and Edit -->
        <div class="flex justify-center items-center mb-6">
          <span class="text-sm text-gray-600">Email: </span>
          <span class="text-sm font-semibold text-gray-800 ml-2">{{ email }}</span>
          <button @click="startEditingEmail" class="ml-2 text-custom-purple">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.864 4.136l3.536 3.536-10.364 10.364-3.536-3.536L16.864 4.136z" />
            </svg>
          </button>
        </div>

        <!-- Resend OTP Button -->
        <button @click="resendOtp" class="text-custom-purple font-semibold mb-4">
          Resend OTP
        </button>

        <!-- Number Pad (Using grid for 3x4 layout with spacing) -->
        <div class="grid grid-cols-3 gap-4 mb-6 w-full max-w-xs mx-auto sm:max-w-sm md:max-w-md">

          <button @click="addDigit(1)" class="pad-btn">1</button>
          <button @click="addDigit(2)" class="pad-btn">2</button>
          <button @click="addDigit(3)" class="pad-btn">3</button>
          <button @click="addDigit(4)" class="pad-btn">4</button>
          <button @click="addDigit(5)" class="pad-btn">5</button>
          <button @click="addDigit(6)" class="pad-btn">6</button>
          <button @click="addDigit(7)" class="pad-btn">7</button>
          <button @click="addDigit(8)" class="pad-btn">8</button>
          <button @click="addDigit(9)" class="pad-btn">9</button>
          <button @click="eraseDigit" class="erase-btn">X</button>
          <button @click="addDigit(0)" class="pad-btn">0</button>
          <button @click="confirmOtp" class="pad-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </button>
        </div>

        <!-- Verify OTP Button -->
        <button @click="submitOtp" class="w-full bg-custom-purple text-white py-3 rounded-lg font-semibold">
          Verify OTP
        </button>

        <!-- Email Editing Screen (shown when editing) -->
        <div v-if="isEditingEmail" class="fixed inset-0 bg-gray-800 bg-opacity-70 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded-lg w-full h-full max-w-full flex flex-col justify-center items-center">
            <!-- Verification Icon in the Email Editing Screen -->
            <div class="bg-[#8541f5] p-5 rounded-full inline-block mb-6">
              <img src="/images/verification-icon.svg" alt="Verification Icon" class="h-16 w-16 mx-auto" />
            </div>

            <h3 class="text-lg font-semibold mb-4">Edit Your Email</h3>
            <input v-model="newEmail" type="email" class="w-full p-3 border border-gray-300 rounded-lg mb-4" placeholder="Enter new email" />
            <button @click="continueEditing" class="w-full bg-[#8541f5] text-white py-3 rounded-lg mt-4">Continue</button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { reactive, ref, onMounted } from 'vue'
  import { router } from '@inertiajs/vue3'

  const otp = reactive(['', '', '', '']) // To store OTP digits
  const email = ref('') // Email initialized to an empty string
  const newEmail = ref('')
  const isEditingEmail = ref(false) // To toggle the email editing modal
  const loading = ref(false) // Loading state

  // Function to get the user data from the API using the access token
  async function getUserInfo() {
    loading.value = true // Start loading spinner

    const accessToken = localStorage.getItem('token');
    console.log('token:', accessToken);

    if (!accessToken) {
      alert('Access token is missing.');
      loading.value = false
      return;
    }

    try {
      const response = await fetch('api/user', {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'Accept': 'application/json',
        },
      });

      // Check if response is OK
      if (!response.ok) {
        throw new Error('Failed to fetch user data');
      }

      const data = await response.json();
      console.log('User data:', data); // Check what data you get back

      // Handle your data (for example, set the email)
      email.value = data.email; // Or update it based on your response
    } catch (err) {
      console.error('Error:', err);
    } finally {
      loading.value = false // Stop loading spinner
    }
  }

  // Add digit to the OTP input
  function addDigit(digit) {
    const index = otp.findIndex(o => o === '')
    if (index !== -1) {
      otp[index] = digit.toString()
    }
  }

  // Erase last entered digit
  function eraseDigit() {
    const lastIndex = otp.findIndex(o => o === '')
    if (lastIndex === -1) {
      otp[otp.length - 1] = '' // Erase last digit if no empty slot
    } else {
      otp[lastIndex - 1] = '' // Erase the digit before the first empty slot
    }
  }


// Submit OTP (for verification)
function submitOtp() {
  const otpCode = otp.join('');
  const token = localStorage.getItem('token');

  if (!token) {
    alert('Token missing. Please log in again.');
    return;
  }

  if (otpCode.length === 4) {
    loading.value = true;

    fetch('/api/verify-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ otp: otpCode })
    })
    .then(response => {
      if (!response.ok) throw new Error('OTP verification failed');
      return response.json();
    })
    .then(data => {
      console.log('Verification success:', data);
      alert('Email verified successfully!');
      router.visit('/proceed');
    })
    .catch(err => {
      console.error('Error:', err);
      alert('Invalid OTP or token');
    })
    .finally(() => {
      loading.value = false;
    });
  } else {
    alert('Please enter a complete OTP.');
  }
}



  // Confirm OTP (additional step)
  function confirmOtp() {
    console.log('OTP Confirmed', otp.join(''))
  }

  // Resend OTP functionality
  function resendOtp() {
    console.log('OTP Resent')
  }

  // Start editing email
  function startEditingEmail() {
    isEditingEmail.value = true
  }

  // Continue editing email and return to OTP screen
  function continueEditing() {
    if (newEmail.value) {
      email.value = newEmail.value
      isEditingEmail.value = false
    } else {
      alert('Please enter a valid email.')
    }
  }

  // Fetch user information when the component is mounted
  onMounted(() => {
    getUserInfo()
  })
  </script>

  <style scoped>
  .otp-input {
    @apply w-16 h-16 text-center text-xl font-semibold border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-purple transition;
  }

  .pad-btn {
    @apply w-16 h-16 bg-gray-100 text-lg font-semibold rounded-lg flex items-center justify-center;
    transition: background-color 0.3s;
  }

  .pad-btn:hover {
    background-color: #8541f5;
    color: white;
  }

  .erase-btn {
    @apply w-16 h-16 bg-gray-100 text-lg font-semibold rounded-lg flex items-center justify-center;
    transition: background-color 0.3s;
  }

  .erase-btn:hover {
    background-color: #ff0000;
    color: white;
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    justify-items: center;
  }

  .fixed {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 50;
  }

  .bg-gray-800 {
    background-color: rgba(0, 0,0, 0.7);
  }

  /* Spinner Styling */
  .animate-spin {
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>
