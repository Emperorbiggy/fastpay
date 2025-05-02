<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
      <!-- Header -->
      <div class="relative flex items-center justify-center px-6 py-5 bg-gradient-to-r from-[#8541f5] to-[#a75ef5] text-white shadow-lg">
        <button @click="goBack" class="absolute left-4 text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>
        <h1 class="text-xl font-semibold">Transfer</h1>
      </div>

      <!-- Scrollable content -->
      <div class="flex-1 overflow-y-auto pb-36">
        <!-- Tabs -->
        <div class="flex mt-4 border-b border-gray-300">
          <button
            :class="{
              'text-white bg-[#8541f5]': activeTab === 'FastPay',
              'text-[#8541f5] bg-white': activeTab !== 'FastPay'
            }"
            class="w-1/2 py-3 text-center font-semibold transition duration-300"
            @click="activeTab = 'FastPay'"
          >
            FastPay
          </button>
          <button
            :class="{
              'text-white bg-[#8541f5]': activeTab === 'Bank',
              'text-[#8541f5] bg-white': activeTab !== 'Bank'
            }"
            class="w-1/2 py-3 text-center font-semibold transition duration-300"
            @click="activeTab = 'Bank'"
          >
            Bank
          </button>
        </div>

        <!-- Amount -->
<div class="mt-6 mx-6">
  <div class="relative">
    <input
      v-model="amount"
      type="number"
      placeholder="Enter amount"
      class="w-full py-4 px-6 rounded-xl bg-white shadow-xl focus:ring-[#8541f5] focus:border-[#8541f5] transition duration-300 border-2 border-gray-300"
    />
    <span class="absolute right-6 top-1/2 transform -translate-y-1/2 text-[#8541f5] text-sm font-semibold">
      ₦{{ currentBalance }}
    </span>
  </div>
  <p v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</p>
</div>


        <!-- FastPay Form -->
        <div v-if="activeTab === 'FastPay'" class="mx-6 mt-8 space-y-6">
          <div>
            <label for="user_id" class="block text-sm font-medium text-gray-800">User Id</label>
            <input
              v-model="user_id"
              type="text"
              id="user_id"
              placeholder="Enter user ID"
              class="w-full py-4 px-6 rounded-xl bg-white shadow-xl border-2 border-gray-300 focus:ring-[#8541f5] focus:border-[#8541f5] transition duration-300"
            />
          </div>

          <div>
            <label for="accountName" class="block text-sm font-medium text-gray-800">Account Name</label>
            <input
              v-model="accountName"
              type="text"
              id="accountName"
              placeholder="Account name will appear here"
              readonly
              class="w-full py-4 px-6 rounded-xl bg-gray-100 text-gray-500 border-2 border-gray-300"
            />
          </div>

          <div>
            <label for="description" class="block text-sm font-medium text-gray-800">Description</label>
            <textarea
              v-model="description"
              id="description"
              placeholder="Enter description"
              class="w-full py-4 px-6 rounded-xl bg-white shadow-xl border-2 border-gray-300 focus:ring-[#8541f5] focus:border-[#8541f5]"
            ></textarea>
          </div>
        </div>

        <!-- Bank Form -->
      <div v-if="activeTab === 'Bank'" class="mx-6 mt-8 space-y-6">
        <div>
          <label for="bank" class="block text-sm font-medium text-gray-800">Bank</label>
          <select v-model="selectedBank" id="bank" class="w-full py-4 px-6 rounded-xl bg-white shadow-xl border-2 border-gray-300 focus:ring-[#8541f5] focus:border-[#8541f5]" @change="verifyAccount">
            <option value="Opay">Opay</option>
            <option value="Zenith Bank">Zenith Bank</option>
            <option value="Wema Bank">Wema Bank</option>
          </select>
        </div>

        <div>
          <label for="accountNumber" class="block text-sm font-medium text-gray-800">Account Number</label>
          <input v-model="accountNumber" type="text" id="accountNumber" placeholder="Enter account number" class="w-full py-4 px-6 rounded-xl bg-white shadow-xl border-2 border-gray-300 focus:ring-[#8541f5] focus:border-[#8541f5]" @change="verifyAccount"/>
        </div>

        <div>
          <label for="accountName" class="block text-sm font-medium text-gray-800">Account Name</label>
          <div class="relative">
            <input v-model="accountName" type="text" id="accountName" placeholder="Account name will appear here" readonly class="w-full py-4 px-6 rounded-xl bg-gray-100 text-gray-500 border-2 border-gray-300" />
            <div v-if="loadingAccountName" class="absolute right-4 top-1/2 transform -translate-y-1/2">
              <svg class="animate-spin h-5 w-5 text-[#8541f5]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
              </svg>
            </div>
          </div>
        </div>
<div v-if="loading" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center z-40">
  <div class="loader"></div>
</div>


          <div>
            <label for="description" class="block text-sm font-medium text-gray-800">Description</label>
            <textarea
              v-model="description"
              id="description"
              placeholder="Enter description"
              class="w-full py-4 px-6 rounded-xl bg-white shadow-xl border-2 border-gray-300 focus:ring-[#8541f5] focus:border-[#8541f5]"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Footer Transfer Button -->
    <div class="fixed bottom-0 w-full bg-white py-4 shadow-lg px-6">
      <button
        :disabled="!isAmountValid || !accountName || !description"
        class="w-full py-4 bg-[#8541f5] text-white text-lg rounded-xl font-semibold disabled:bg-gray-300 hover:bg-[#6e3ee0] transition duration-300"
        @click="makeTransfer"
      >
        Transfer
      </button>
    </div>
    </div>
  </template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const activeTab = ref('FastPay')
const amount = ref('')
const accountName = ref('')
const description = ref('')
const accountNumber = ref('')
const selectedBank = ref('')
const currentBalance = ref(0)
const errorMessage = ref('')
const user_id = ref('')
const loadingAccountName = ref(false)
const loading = ref(false)

// Demo account details
const demoAccountNumber = '9139986596'
const demoAccountName = 'Ayodeji Samuel Ojeva'

// Fetch balance on mount
onMounted(async () => {
  await fetchBalance()
})

const fetchBalance = async () => {
  try {
    const response = await fetch('/api/balance', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
    })
    const data = await response.json()
    if (data.balance !== undefined) {
      currentBalance.value = parseFloat(data.balance)
    }
  } catch (error) {
    console.error('Error fetching balance:', error)
  }
}
const verifyAccount = () => {
  if (accountNumber.value === demoAccountNumber && selectedBank.value === 'Opay') {
    accountName.value = demoAccountName
    errorMessage.value = ''
  } else {
    accountName.value = ''
    errorMessage.value = 'Invalid account number or bank'
  }
}

// Watch for changes in user_id and verify account
watch(user_id, async (newVal) => {
  if (newVal.length === 10) {
    loading.value = true // Show loader before making the API request
    try {
      const response = await fetch(`/api/verify-account/${newVal}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      })
      const data = await response.json()

      if (data.success) {
        accountName.value = data.full_name
        errorMessage.value = ''
      } else {
        accountName.value = ''
        errorMessage.value = data.message || 'Account not found'
      }
    } catch (error) {
      accountName.value = ''
      errorMessage.value = 'Failed to verify account'
    } finally {
      loading.value = false // Hide loader once the request is finished
    }
  } else {
    accountName.value = ''
    loading.value = false // Ensure loader is hidden if user_id is not valid
  }
})


// Validate amount
const isAmountValid = computed(() => {
  const amt = parseFloat(amount.value)
  if (isNaN(amt)) {
    errorMessage.value = ''
    return false
  }

  if (amt < 100) {
    errorMessage.value = 'Minimum amount is ₦100'
    return false
  }

  if (amt > currentBalance.value) {
    errorMessage.value = 'Insufficient balance'
    return false
  }

  errorMessage.value = ''
  return true
})

const makeTransfer = () => {
  const isFastPay = activeTab.value === 'FastPay'

  const transferData = {
    amount: amount.value,
    description: description.value,
    method: activeTab.value,
    type: isFastPay ? 'wallet' : 'bank',
    account_name: accountName.value,
    bank: isFastPay ? 'FastPay' : selectedBank.value, // Updated bank name logic
    ...(isFastPay
      ? { user_id: user_id.value }
      : { account_number: accountNumber.value })
  }

  // Store the transfer data in local storage
  localStorage.setItem('transferData', JSON.stringify(transferData))

  Inertia.visit(route('confirm'), {
    method: 'get',
  })
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
