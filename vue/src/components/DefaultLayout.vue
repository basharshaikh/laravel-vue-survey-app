<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="flex h-screen overflow-hidden">
    <Sidebar />
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        <router-view></router-view>
    </div>
    <Notification/>
  </div>
</template>

<script setup>
import Notification from './Notification.vue';
import Sidebar from './_partials/Sidebar.vue'
</script>