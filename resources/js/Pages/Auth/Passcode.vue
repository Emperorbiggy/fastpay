<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12 relative">
      <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8 relative">
        <!-- Title -->
        <h2 class="text-2xl font-bold text-center mb-4">Create Passcode</h2>
        <p class="text-center text-sm text-gray-500 mb-8">Please create a 4-digit passcode to secure your account.</p>

        <!-- Passcode Form -->
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- Enter Passcode -->
          <div v-if="step === 1" class="flex flex-col items-center mb-8">
            <h3 class="text-xl font-semibold text-center mb-4">Enter your passcode</h3>
            <div class="flex space-x-4 mb-6">
              <input v-model="form.passcode[0]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.passcode[1]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.passcode[2]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.passcode[3]" type="text" class="passcode-input" maxlength="1" readonly />
            </div>
          </div>

          <!-- Confirm Passcode -->
          <div v-if="step === 2" class="flex flex-col items-center mb-8">
            <h3 class="text-xl font-semibold text-center mb-4">Confirm your passcode</h3>
            <div class="flex space-x-4 mb-6">
              <input v-model="form.confirmPasscode[0]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.confirmPasscode[1]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.confirmPasscode[2]" type="text" class="passcode-input" maxlength="1" readonly />
              <input v-model="form.confirmPasscode[3]" type="text" class="passcode-input" maxlength="1" readonly />
            </div>
          </div>

          <!-- Number Pad -->
          <div class="grid grid-cols-3 gap-6 mb-6 justify-center mx-auto">
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

          <!-- Next Button -->
          <div v-if="step === 1" class="flex justify-center">
            <button @click.prevent="nextStep" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold mb-6">
              Next
            </button>
          </div>

          <!-- Submit Button -->
          <div v-if="step === 2" class="flex justify-center">
            <button @click.prevent="submitPasscode" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold mb-6">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { reactive, ref } from 'vue'

  const form = reactive({
    passcode: ['', '', '', ''],
    confirmPasscode: ['', '', '', '']
  })

  const step = ref(1)

  function nextStep() {
    // Check if passcode is complete
    if (form.passcode.every((digit) => digit !== '')) {
      step.value = 2
    } else {
      alert('Please enter a 4-digit passcode.')
    }
  }

  function submitPasscode() {
    // Verify if passcode and confirm passcode match
    if (form.passcode.join('') === form.confirmPasscode.join('')) {
      alert('Passcode created successfully!')
      // Handle successful passcode creation (e.g., save to server or navigate to another page)
    } else {
      alert('Passcodes do not match. Please try again.')
    }
  }

  function addDigit(digit) {
    const passcodeArray = step.value === 1 ? form.passcode : form.confirmPasscode
    for (let i = 0; i < passcodeArray.length; i++) {
      if (passcodeArray[i] === '') {
        passcodeArray[i] = digit.toString()
        break
      }
    }
  }

  function eraseDigit() {
    const passcodeArray = step.value === 1 ? form.passcode : form.confirmPasscode
    for (let i = passcodeArray.length - 1; i >= 0; i--) {
      if (passcodeArray[i] !== '') {
        passcodeArray[i] = ''
        break
      }
    }
  }

  function confirmOtp() {
    submitPasscode()
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
    position: relative;
  }

  .passcode-input:focus {
    outline: none;
    border-color: #8541f5;
  }

  .pad-btn {
    width: 60px;
    height: 60px;
    font-size: 24px;
    background-color: #f0f0f0;
    border-radius: 8px;
    border: none;
    transition: background-color 0.3s;
  }

  .pad-btn:hover {
    background-color: #8541f5;
  }

  .erase-btn {
    width: 60px;
    height: 60px;
    font-size: 24px;
    background-color: #f0f0f0;
    border-radius: 8px;
    border: none;
    transition: background-color 0.3s;
  }

  .erase-btn:hover {
    background-color: #8541f5;
  }

  button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  </style>
