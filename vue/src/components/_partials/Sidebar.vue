<template>
    <div v-if="showAside" class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-56 bg-stone-900 shadow-md py-4 transition-all duration-200 ease-in-out">
        <!-- sidebar header -->
        <div class="flex justify-between mb-2 px-3 sm:px-2">
            <img class="w-20" src="../../assets/images/backend-logo-.png" alt="backend-logo">
            <ArrowLeftIcon @click="toggleSidebar(showAside)" class="w-5 text-slate-400 cursor-pointer lg:hidden ms:block"/>
        </div>

        <!-- Sidebar links -->
        <div class="mt-8">
            <router-link to="/dashboard" class="text-slate-300 py-3 px-3 block text-md font-semibold border-y border-stone-700">Dashboard</router-link>
            <router-link to="/surveys" class="text-slate-300 py-3 px-3 block text-md font-semibold border-b border-stone-700">All Surveys</router-link>
            <router-link to="/answers" class="text-slate-300 py-3 px-3 block text-md font-semibold border-b border-stone-700">All Answers</router-link>
            <router-link to="/surveys/create" class="text-slate-300 py-3 px-3 block text-md font-semibold border-b border-stone-700">Add New</router-link>
        </div>
    </div>
</template>
<style>
.router-link-active{
    @apply bg-stone-700 text-white transition
}
</style>
<script setup>
import { ArrowLeftIcon } from '@heroicons/vue/solid';
import store from '../../store';
import {computed} from 'vue'
const showAside = computed(() => store.state.sidebar.show)

function toggleSidebar(showAside){
    store.commit("sidebarToggle", {
        show: !showAside
    })
}

</script>   