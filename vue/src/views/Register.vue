<template>
    <div>
      <img class="w-20 m-auto" src="../assets/images/backend-logo.png" alt="backend-logo">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        {{ ' ' }}
        <router-link :to="{name: 'Login'}"  class="font-medium text-indigo-600 hover:text-indigo-500">
          Login to your account
        </router-link>
      </p>
    </div>
    <form class="mt-8 space-y-6" @submit="register">
      <Alert v-if="Object.keys(errors).length" class="flex-col items-stretch text-sm">
        <div v-for="(field, i) of Object.keys(errors)" :key="i">
          <div v-for="(error, ind) of errors[field] || []" :key="ind">
            *{{error}}
          </div>
        </div>
      </Alert>

      <input type="hidden" name="remember" value="true" />
      <div class="rounded-md shadow-sm -space-y-px">

        <div>
          <label for="full-name" class="sr-only">Full Name</label>
          <input v-model="user.name" id="full-name" name="fullname" type="text" required="" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Full Name" />
        </div>

        <div>
          <label for="email-address" class="sr-only">Email address</label>
          <input v-model="user.email" id="email-address" name="email" type="email" autocomplete="email" required="" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address" />
        </div>

        <div>
          <label for="password" class="sr-only">Password</label>
          <input v-model="user.password" id="password" name="password" type="password" autocomplete="current-password" required="" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password" />
        </div>

        <div>
          <label for="password_confirmation" class="sr-only">Password Confirmation</label>
          <input v-model="user.password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="current-password" required="" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password Confirmation" />
        </div>

      </div>

      <div>
        <button 
        :disabled="loading"
        :class="{'cursor-not-allowed': loading}"
        type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" aria-hidden="true" />
          </span>
          <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Sign Up
        </button>
      </div>
    </form>
    
</template>

<script setup>
import { LockClosedIcon } from '@heroicons/vue/solid'
import { useRouter } from 'vue-router';
import store from '../store/index.js';
import { ref } from "vue";
import Alert from '../components/Alert.vue';

const router = useRouter()

const user = {
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
}

const errors = ref({});
const loading = ref(false);

function register(e){
  e.preventDefault()
  loading.value = true;
  // promise -- bellow rigister action in store
  store
    .dispatch('register', user)
    .then( (res) => {
      loading.value = false;
      router.push({name: 'Dashboard'})
    })
    .catch((err) => {
      loading.value = false;
      if(err.response.status === 422){
        errors.value = err.response.data.errors
      }
    })
}















</script>