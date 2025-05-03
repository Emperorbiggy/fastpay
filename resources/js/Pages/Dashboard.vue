<template>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-between">
      <!-- Top Card -->
      <div class="bg-[#8541f5] text-white p-6 rounded-b-3xl shadow-md">
        <div class="flex justify-between items-start">
          <!-- Profile Image Icon -->
          <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#8541f5]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

          </div>

          <!-- Time Greeting -->
          <div class="text-center text-sm font-light">
            <p v-if="showSunrise">{{ alarmIcon }}</p>
            <p class="text-sm">Good {{ greetingTime }}</p>
          </div>

          <!-- Notification Icon -->
          <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#8541f5]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a2 2 0 10-4 0v.083A6 6 0 004 11v3.159c0 .538-.214 1.055-.595 1.436L2 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
            </svg>
          </div>
        </div>

        <!-- Balance Info -->
        <div class="mt-6">
          <p class="text-sm text-white/80">Current Balance</p>
          <h2 class="text-3xl font-bold mt-1">₦{{ balance }}</h2>

          <!-- Transfer Button -->
          <button @click="goToTransfer" class="mt-6 w-full bg-white text-[#8541f5] py-3 rounded-xl font-semibold flex items-center justify-center gap-2 hover:bg-gray-100 transition">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
  </svg>
  Transfer
</button>

        </div>
      </div>
      <div v-if="loading" class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-opacity-50 bg-gray-800 z-50">
        <div class="text-center">
          <div class="border-t-4 border-b-4 border-custom-purple w-16 h-16 rounded-full animate-spin mx-auto"></div>
          <p class="text-white mt-4">Please wait...</p>
        </div>
      </div>

      <!-- Weather Update -->
      <div v-if="weather" class="bg-[#8541f5] text-white p-4 mt-6 mx-4 rounded-xl shadow-md">
        <h3 class="text-lg font-semibold mb-2">Weather Update</h3>
        <div class="flex justify-between text-sm">
          <div>
            <p>🌤️ {{ weather.city }}</p>
            <p>{{ weather.temp }}°C, {{ weather.condition }}</p>
          </div>
          <div class="text-right">
            <p>Feels like: {{ weather.feelsLike }}°C</p>
            <p>Humidity: {{ weather.humidity }}%</p>
          </div>
        </div>
      </div>

      <!-- Transaction History -->
      <div class="mt-6 mx-4">
        <div class="flex justify-between items-center mb-2">
          <h3 class="text-lg font-semibold">Transactions</h3>
          <button class="text-sm text-[#8541f5] font-semibold">View all</button>
        </div>

        <div v-for="(txn, index) in transactions" :key="index" class="bg-white p-4 rounded-lg shadow mb-3 flex justify-between items-center">
          <div>
            <p class="font-medium">Bank Transfer</p>
            <p class="text-sm text-gray-500">{{ txn.date }}</p>
          </div>
          <div class="text-right">
            <p class="text-[#8541f5] font-bold">+₦{{ txn.amount }}</p>
            <p class="text-xs text-gray-400">{{ txn.time }}</p>
          </div>
        </div>
      </div>

      <!-- Footer Navigation -->
      <div class="mt-6 bg-white py-3 px-6 rounded-t-3xl shadow-inner">
        <div class="flex justify-between items-center text-sm text-[#8541f5]">
          <!-- Home -->
          <div class="flex flex-col items-center">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m4-6v6m4-6v6" /></svg>
            <span class="text-xs">Home</span>
          </div>

          <!-- Send (Center) -->
          <div @click="goToTransfer" class="bg-[#8541f5] text-white rounded-full p-4 -mt-8 shadow-md flex flex-col items-center">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m-8-8v16" />
  </svg>
  <span class="text-xs mt-1">Send</span>
</div>




  <div class="flex flex-col items-center cursor-pointer" @click="logout">
  <!-- Logout Icon -->
  <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
  </svg>
  <span class="text-xs">Logout</span>
</div>



        </div>
      </div>
    </div>
  </template>


<script setup>
import { ref, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { router } from '@inertiajs/vue3'


const loading = ref(false)
const hour = new Date().getHours()
const greetingTime = ref('')
const alarmIcon = ref('')
const showSunrise = ref(false)
const balance = ref(0) // Add a ref for balance
const logout = () => {
  loading.value = true
  localStorage.removeItem('token') // only remove token
  router.visit('/')
}


// Function to fetch balance with token from localStorage
const fetchBalance = async () => {
  try {
    const response = await fetch('/api/balance', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`, // Add token from localStorage
      },
    })
    const data = await response.json()
    if (data.balance) {
      balance.value = data.balance
    }
  } catch (error) {
    console.error('Error fetching balance:', error)
  }
}

if (hour >= 0 && hour < 12) {
  greetingTime.value = 'Morning'
  alarmIcon.value = 'Sunrise ⏰'
  showSunrise.value = true
} else if (hour >= 12 && hour < 17) {
  greetingTime.value = 'Afternoon'
  alarmIcon.value = 'Sunset ⏰'
  showSunrise.value = false
} else {
  greetingTime.value = 'Evening'
  alarmIcon.value = 'Night ⏰'
  showSunrise.value = false
}
const goToTransfer = () => {
  loading.value = true // Show loader

  // Simulate a delay for the transfer action
  setTimeout(() => {
    Inertia.visit('/transfer')
    loading.value = false // Hide loader once the page is visited
  }, 1000) // You can adjust this delay or remove it for real action
}
// Transactions data
const transactions = ref([
  { date: 'Apr 30, 2025', time: '09:23 AM', amount: '50,000' },
  { date: 'Apr 29, 2025', time: '04:55 PM', amount: '30,000' },
])

// Locations for weather data
const locations = [
  { city: 'Osun', lat: 7.5629, lon: 4.5199 },
  { city: 'Abuja', lat: 9.0578, lon: 7.4951 },
  { city: 'Lagos', lat: 6.5244, lon: 3.3792 },
  { city: 'Port Harcourt', lat: 4.8156, lon: 7.0498 },
  { city: 'Kano', lat: 12.0022, lon: 8.5919 },
]

const weather = ref({
  city: '',
  temp: '',
  condition: 'Loading...',
  feelsLike: '',
  humidity: ''
})

let currentIndex = 0

// Function to fetch weather data for a specific location
const fetchWeather = async (location) => {
  try {
    const response = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${location.lat}&longitude=${location.lon}&current_weather=true`)
    const data = await response.json()
    weather.value = {
      city: location.city,
      temp: data.current_weather.temperature,
      condition: 'Partly Cloudy',
      feelsLike: data.current_weather.temperature,
      humidity: 65 // static for now
    }
  } catch (error) {
    console.error(`Weather fetch failed for ${location.city}:`, error)
  }
}

// Fetch balance and weather data on component mount
onMounted(() => {
  fetchBalance()
  fetchWeather(locations[currentIndex]) // Load the first one immediately

  // Change weather location every 5 seconds
  setInterval(() => {
    currentIndex = (currentIndex + 1) % locations.length
    fetchWeather(locations[currentIndex])
  }, 5000)
})
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
