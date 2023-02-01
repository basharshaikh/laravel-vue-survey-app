<template>
    <header class="bg-stone-500 h-[64px] inline-flex justify-between py-3 px-3 lg:px-6 shadow-sm">
        <!-- berger menu -->
        <div class="flex place-items-center">
            <BarThree @click="toggleSidebar(showAside)" class="lvs-rounded-icon lg:hidden sm:block !rounded-md"/>
        </div>

        <!-- user icons -->
        <div class="flex place-items-center">
            <SearchIcon class="lvs-rounded-icon"/>
            <BellIcon class="lvs-rounded-icon"/>
            <LogoutIcon @click="logout" class="lvs-rounded-icon !mr-0"/>
        </div>
    </header>
    <div v-if="showAside" @click="toggleSidebar(showAside)" class="fixed w-full h-full top-0 left-0 bg-slate-600 opacity-50 -z-1 lg:hidden sm:block"></div>
</template>

<script setup>
import { BellIcon, SearchIcon, LogoutIcon} from '@heroicons/vue/solid';
import BarThree from '../_icons/BarThree.vue'
import store from '../../store'
import { useRouter } from 'vue-router';
import {computed} from 'vue'
const showAside = computed(() => store.state.sidebar.show)

const router = useRouter()

function toggleSidebar(showAside){
    store.commit("sidebarToggle", {
        show: !showAside
    })
}

function logout(){
    store.dispatch('logout') 
    .then(() => {
        router.push({
            name: 'Login'
        })
    })
}
</script>