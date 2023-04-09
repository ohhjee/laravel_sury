<template>
    <div>
        <div>
            <img class="mx-auto h-12 w-auto" src="../../assets/images/logo/mark.svg" alt="Your Company" />
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Register for free</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or {{ ' ' }}
                <router-link :to="{name:'Login'}" class="font-medium text-indigo-600 hover:text-indigo-500">login to your account</router-link>
            </p>
        </div>
        <!-- <pre>{{errorMsg}}</pre> -->
        <form class="mt-8 space-y-6" action="#" @submit.prevent="register()">
         <Alert v-if="Object.keys(errorMsg).length">
             <!-- {{errorMsg}} -->
             <!-- <span class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]" @click="errorMsg=''">
               <XMarkIcon class="text-white"/>
             </span> -->
<div v-for="(field, i) of Object.keys(errorMsg)" :key="i">
<div v-for="(error,ind) of errorMsg[field] || []" :key="ind">
    *{{error}}
</div>
</div>
         </Alert>
            <!-- <div class="py-3 px-5 bg-red-500 text-white rounded space-x-[3rem] flex items-center justify-between" > -->
          <!-- </div> -->
            <input type="hidden" name="remember" value="true" />
            <div class="-space-y-px rounded-md shadow-sm">
                <div>
                    <label for="full-name" class="sr-only">Full Name</label>
                    <input id="full-name" name="name" type="text" v-model="user.name" autocomplete="email" required class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        placeholder="Full Name" />
                </div>
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" v-model="user.email" autocomplete="email" required class="relative block w-full appearance-none rounded-none border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        placeholder="Email address" />
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" v-model="user.password" autocomplete="current-password" required class="relative block w-full appearance-none border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        placeholder="Password" />
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" v-model="user.password_confirmation" type="password" autocomplete="current-password" required class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        placeholder="Password Confirmation" />
                </div>
            </div>
    
            <div>
                <button :disable="loading" type="submit" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"
                    :class="{'cursor-not-allowed':loading, 'hover:bg-indigo-500' : loading}"
                    >
                      <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" aria-hidden="true" />
                    </span>
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sign up
                  </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { LockClosedIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { useRouter } from 'vue-router';
import store from '../../store';
import {ref} from 'vue'
import Alert from '../../components/Alert.vue';
const user = {
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
}
const errorMsg=ref<string>('')
const router = useRouter();
const loading =ref<boolean>(false)


function register() {
    loading.value=true
    store.dispatch('register', user).then(() => {
  loading.value=false
  
  router.push({
      name: 'Dashboard'
    })
}).catch(err=>{
    if(err.response.status === 422){
        errorMsg.value = err.response.data.errors 
        loading.value=false
      }
    })
}
</script>

<style>

</style>