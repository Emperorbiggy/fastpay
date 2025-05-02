<!-- resources/js/components/Input.vue -->
<template>
    <input
      v-bind="$attrs"
      :value="modelValue"
      @input="$emit('update:modelValue', $event)" <!-- Emit the update:modelValue event on input change -->
      class="w-full p-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
      :type="type"
      :placeholder="placeholder"               
    />
  </template>

  <script setup>
  const props = defineProps({
    modelValue: {                               // The model value for v-model
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'text'                           // Default input type is 'text'
    },
    placeholder: {
      type: String,
      default: ''                               // Default placeholder is an empty string
    },
  })

  const emit = defineEmits(['update:modelValue'])  // Emit the update:modelValue event to update the parent
  </script>
