<template>
    <header class="bg-stone-500 h-[64px] inline-flex justify-between py-3 px-6 shadow-sm">
        <!-- berger menu -->
        <div class="flex place-items-center">
            <BarThree class="lvs-rounded-icon lg:hidden sm:block"/>
        </div>

        <!-- user icons -->
        <div class="flex place-items-center">
            <SearchIcon class="lvs-rounded-icon"/>
            <BellIcon class="lvs-rounded-icon"/>
            <LogoutIcon @click="logout" class="lvs-rounded-icon !mr-0"/>
        </div>
    </header>
</template>

<script setup>
import { BellIcon, SearchIcon, LogoutIcon} from '@heroicons/vue/solid';
import BarThree from '../_icons/BarThree.vue'
import store from '../../store'
import { useRouter } from 'vue-router';
const router = useRouter()

function logout(){
    store.dispatch('logout') 
    .then(() => {
        router.push({
            name: 'Login'
        })
    })
}
</script>