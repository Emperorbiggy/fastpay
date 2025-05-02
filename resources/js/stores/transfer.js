import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useTransferStore = defineStore('transfer', () => {
  const amount = ref('')
  const accountName = ref('')
  const bank = ref('')
  const description = ref('')
  const user_id = ref('')
  const accountNumber = ref('')
  const method = ref('')

  // This method is used to set all the data in the store
  const setTransfer = (data) => {
    amount.value = data.amount
    accountName.value = data.account_name
    bank.value = data.bank
    description.value = data.description
    user_id.value = data.user_id
    accountNumber.value = data.account_number
    method.value = data.method
  }

  return {
    amount,
    accountName,
    bank,
    description,
    user_id,
    accountNumber,
    method,
    setTransfer
  }
})
