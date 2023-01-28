<template>
    <div class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-56 bg-stone-900 shadow-md py-4 transition-all duration-200 ease-in-out">
        <!-- sidebar header -->
        <div class="flex justify-between mb-2 px-3 sm:px-2">
            <img class="w-20" src="../../assets/images/backend-logo-.png" alt="backend-logo">
            <ArrowLeftIcon class="w-5 text-slate-400 cursor-pointer lg:hidden ms:block"/>
        </div>

        <!-- Sidebar links -->
        <div class="mt-8">
            <div class="bg-stone-800 border-y-[1px] border-stone-300">
                <router-link to="/dashboard" class="text-white py-3 px-3 inline-block text-md font-semibold">Dashboard</router-link>
            </div>
            <div class="bg-stone-800 border-b-[1px] border-stone-600">
                <router-link to="/surveys" class="text-slate-300 py-3 px-3 inline-block text-md font-semibold">All Surveys</router-link>
            </div>
            <div class="bg-stone-800 border-b-[1px] border-stone-600">
                <router-link to="/answers" class="text-slate-300 py-3 px-3 inline-block text-md font-semibold">All Answers</router-link>
            </div>
            <div class="bg-stone-800 border-b-[1px] border-stone-600">
                <router-link to="/surveys/create" class="text-slate-300 py-3 px-3 inline-block text-md font-semibold">Add New</router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ArrowLeftIcon } from '@heroicons/vue/solid';
</script>   