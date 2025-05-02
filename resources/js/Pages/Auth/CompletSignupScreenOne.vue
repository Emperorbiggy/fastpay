<template>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
      <div class="w-full max-w-lg bg-white rounded-lg shadow-md p-8">
        <!-- Logo centered -->
        <div class="flex justify-center mb-6">
          <img src="/images/logo.svg" alt="Logo" class="h-12" />
        </div>

        <!-- Heading -->
        <h2 class="text-2xl font-bold text-center mb-4">Complete Sign Up</h2>
        <p class="text-center text-sm text-gray-500 mb-8">Please provide your address information to continue.</p>

        <!-- Form Inputs -->
        <form @submit.prevent="submitForm" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input v-model="form.address" type="text" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Enter your address" required />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
            <input v-model="form.state" type="text" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Enter your state" required />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
            <input v-model="form.city" type="text" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Enter your city" required />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <select v-model="form.country" class="w-full p-3 border border-gray-300 rounded-lg" required>
              <option value="" disabled>Select a country</option>
              <option value="Canada">Canada</option>
              <option value="United States">United States</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="Nigeria">Nigeria</option>
              <option value="Ghana">Ghana</option>
              <!-- Add more countries if needed -->
            </select>
          </div>

          <!-- Continue Button -->
          <button type="submit" class="w-full bg-[#8541f5] text-white py-3 rounded-lg font-semibold">
            Continue
          </button>
        </form>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center z-40">
        <div class="loader"></div>
      </div>
    </div>
  </template>

  <script setup>
  import { reactive, ref } from 'vue'

  const form = reactive({
    address: '',
    state: '',
    city: '',
    country: ''
  })

  const loading = ref(false) // Track loader state

  function submitForm() {
    const token = localStorage.getItem('token')

    if (!token) {
      alert('Token missing. Please log in again.')
      return
    }

    loading.value = true  // Show loader when submitting the form

    fetch('/api/update-user', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  },
  body: JSON.stringify(form)
})
  .then(response => {
    if (!response.ok) throw new Error('Failed to update user info');
    return response.json();
  })
  .then(data => {
    alert('User info updated successfully!');
    console.log(data);

    // Navigate to the "pin" page or route after success
    window.location.href = '/pin'; // Or use a routing method if using a frontend framework
  })
  .catch(error => {
    console.error(error);
  })
  .finally(() => {
    loading.value = false; // Hide loader after the request is completed
  });

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
