<template>
    <button class="flex items-center border w-full justify-center mr-2 py-2 rounded-lg" @click="handleLogin">
      <img src="" class="h-5 w-5 mr-2" /> Google
    </button>
  </template>

  <script setup>
  function handleLogin() {
    console.log('Google login initiated');
    // Integrate Google login logic here
  }
  </script>
