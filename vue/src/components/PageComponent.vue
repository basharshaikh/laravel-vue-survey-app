<template>
  <Header />
  <slot name="header"></slot>
  <main class="p-6">
    <slot></slot>
  </main>
</template>

<script setup>
import Header from './_partials/Header.vue';
const props = defineProps({
    title: String
})
</script>