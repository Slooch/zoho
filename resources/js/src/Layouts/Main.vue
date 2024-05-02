<script setup>
import { computed, ref } from 'vue'
import consts from '@/src/consts.js'
import { store } from '@/src/store'

import Sidebar from '@/src/Components/Sidebar.vue'
import Section from '@/src/Components/Section.vue'
import Dashboard from '@/src/Views/Dashboard.vue'
import Accounts from '@/src/Views/Accounts.vue'
import Account from '@/src/Views/Account.vue'

import ButtonGetStarted from '@/src/Components/Buttons/GetStarted.vue'
import DialogGetStarted from '@/src/Views/Dialogs/GetStarted.vue'
import AddNewAccount from '@/src/Views/Forms/AddNewAccount.vue'

// a computed ref
const mainCmpN = computed(() => {
   return store.mainCmpN
})

const mainAccounts = computed(() => {
   return store.mainAccounts
})
const mainAccount = computed(() => {
   return store.mainAccount
})

defineProps({
   title: {
      type: String,
      default: 'Confirm Password'
   },
   content: {
      type: String,
      default: 'For your security, please confirm your password to continue.'
   },
   button: {
      type: String,
      default: 'Confirm'
   }
})

const refAddNewAccount = ref()
const refDialogGetStarted = ref()

const onDialogGetStartedSubmitted = () => {
   refAddNewAccount.value.onSubmit(refDialogGetStarted.value.close)
}
consts.refresMainAccounts()

</script>
<template>
  <div class="flex">
    <Sidebar />

    <Section>
      <template v-slot:header>
        <!-- content for the header slot -->
        <div v-if="mainCmpN==='dashboard'">

          <div>

            <div class="bg-white relative  p-4 pb-5 lg:pt-16 dark:bg-gray-900">
              <div class="relative xl:container m-auto px-6 md:px-12 lg:px-6">
                <div class="pt-10 pl-10">
                  <ButtonGetStarted> Get Started</ButtonGetStarted>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <Dashboard v-if="mainCmpN==='dashboard'" />
      <Accounts v-if="mainCmpN==='accounts' && mainAccounts.length" />
      <Account v-if="mainCmpN==='account' && mainAccounts.length" />
      <!-- Default Modal -->
      <!-- -->
      <DialogGetStarted ref="refDialogGetStarted" :button="'Add'" @submitted="onDialogGetStartedSubmitted">
        <template v-slot:header>
          New Account
        </template>
        <AddNewAccount ref="refAddNewAccount" />
      </DialogGetStarted>

    </Section>

  </div>
</template>
