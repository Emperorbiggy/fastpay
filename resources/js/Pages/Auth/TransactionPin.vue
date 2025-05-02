<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12 relative">
      <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8 relative">
        <!-- Title -->
        <h2 class="text-2xl font-bold text-center mb-4">Create Transaction PIN</h2>
        <p class="text-center text-sm text-gray-500 mb-8">Set a 4-digit transaction PIN for secure payments.</p>

        <!-- PIN Form -->
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- Enter PIN -->
          <div v-if="step === 1" class="flex flex-col items-center mb-8">
            <h3 class="text-xl font-semibold text-center mb-4">Enter your transaction PIN</h3>
            <div class="flex space-x-4 mb-6">
              <input v-model="form.pin[0]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.pin[1]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.pin[2]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.pin[3]" type="text" class="passcode-input" maxlength="1" readonly />
            </div>
          </div>

          <!-- Confirm PIN -->
          <div v-if="step === 2" class="flex flex-col items-center mb-8">
            <h3 class="text-xl font-semibold text-center mb-4">Confirm your transaction PIN</h3>
            <div class="flex space-x-4 mb-6">
              <input v-model="form.confirmPin[0]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.confirmPin[1]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.confirmPin[2]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.confirmPin[3]" type="text" class="passcode-input" maxlength="1" readonly />
            </div>
          </div>

          <!-- Number Pad -->
          <div class="grid grid-cols-3 gap-6 mb-6 justify-center mx-auto">
            <button @click="addDigit(1)" type="button" class="pad-btn">1</button>
            <button @click="addDigit(2)" type="button" class="pad-btn">2</button>
            <button @click="addDigit(3)" type="button" class="pad-btn">3</button>
            <button @click="addDigit(4)" type="button" class="pad-btn">4</button>
            <button @click="addDigit(5)" type="button" class="pad-btn">5</button>
            <button @click="addDigit(6)" type="button" class="pad-btn">6</button>
            <button @click="addDigit(7)" type="button" class="pad-btn">7</button>
            <button @click="addDigit(8)" type="button" class="pad-btn">8</button>
            <button @click="addDigit(9)" type="button" class="pad-btn">9</button>
            <button @click="eraseDigit" type="button" class="erase-btn">X</button>
            <button @click="addDigit(0)" type="button" class="pad-btn">0</button>
            <button @click="confirmOtp" type="button" class="pad-btn">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </button>
          </div>

          <!-- Next Button -->
          <div v-if="step === 1" class="flex justify-center">
            <button @click.prevent="nextStep" type="button" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold mb-6">
              Next
            </button>
          </div>

          <!-- Submit Button -->
          <div v-if="step === 2" class="flex justify-center">
            <button @click.prevent="submitPin" type="button" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold mb-6">
              Submit
            </button>
          </div>

          <!-- Custom Loader -->
          <div v-if="loading" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center z-40">
            <div class="loader"></div>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const form = reactive({
  pin: ['', '', '', ''],
  confirmPin: ['', '', '', '']
})

const step = ref(1)
const loading = ref(false)
const token = localStorage.getItem('token')

function nextStep() {
  const pinValue = form.pin.join('')
  if (pinValue.length === 4) {
    step.value = 2
  } else {
    alert('Please enter a valid 4-digit PIN before proceeding.')
  }
}

function submitPin() {
  const pinValue = form.confirmPin.join('')
  if (pinValue.length === 4 && form.pin.join('') === form.confirmPin.join('')) {
    loading.value = true
    fetch('/api/update-pin', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ pin: form.pin })
    })
      .then(res => {
        if (!res.ok) throw new Error('Failed to update PIN')
        return res.json()
      })
      .then(() => {
        alert('PIN updated successfully!')
        Inertia.visit('/welcome')
      })
      .catch(error => {
        console.error(error)
        alert('Error updating PIN')
      })
      .finally(() => {
        loading.value = false
      })
  } else {
    alert('PINs do not match or are incomplete.')
  }
}

function addDigit(digit) {
  const current = step.value === 1 ? form.pin : form.confirmPin
  for (let i = 0; i < current.length; i++) {
    if (current[i] === '') {
      current[i] = digit.toString()
      break
    }
  }
}

function eraseDigit() {
  const current = step.value === 1 ? form.pin : form.confirmPin
  for (let i = current.length - 1; i >= 0; i--) {
    if (current[i] !== '') {
      current[i] = ''
      break
    }
  }
}

function confirmOtp() {
  submitPin()
}

  </script>

  <style scoped>
  .passcode-input {
    width: 50px;
    height: 50px;
    font-size: 24px;
    text-align: center;
    border: 2px solid #ccc;
    border-radius: 8px;
    margin-right: 8px;
  }

  .passcode-input:focus {
    outline: none;
    border-color: #8541f5;
  }

  .pad-btn,
  .erase-btn {
    width: 60px;
    height: 60px;
    font-size: 24px;
    background-color: #f0f0f0;
    border-radius: 8px;
    border: none;
    transition: background-color 0.3s;
  }

  .pad-btn:hover,
  .erase-btn:hover {
    background-color: #8541f5;
    color: white;
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
