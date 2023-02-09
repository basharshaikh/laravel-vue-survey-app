<template>
  <Header />
  <slot name="header"></slot>
  <main class="p-6">
    <slot></slot>
  </main>
  <Footer/>
</template>

<script setup>
import Header from './_partials/Header.vue';
import Footer from './_partials/Footer.vue';
const props = defineProps({
    title: String
})
</script>