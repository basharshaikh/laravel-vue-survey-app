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
            <UserIcon class="lvs-rounded-icon !mr-0"/>
        </div>
    </header>
</template>

<script setup>
import { UserIcon, BellIcon, SearchIcon} from '@heroicons/vue/solid';
import BarThree from '../_icons/BarThree.vue'
function logout(){
    store.dispatch('logout') 
    .then(() => {
        router.push({
            name: 'Login'
        })
    })
}
</script>