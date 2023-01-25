<template>
  <PageComponent title="Dashboard">
    <diV v-if="loading" class="flex justify-center relative">Loading...</diV>
    <div v-else
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700"
    >
      <!-- Total surveys -->
      <div class="bg-white shadow-md p-3 text-center flex flex-col order-1 lg:order-2">
        <h3 class="text-3xl font-semibold">Total Surveys</h3>
        <div class="text-6xl font-semibold flex-1 flex items-center justify-center">{{data.totalSurveys}}</div>
      </div>

      <!-- Total Answers -->
      <div class="bg-white shadow-md p-3 text-center flex flex-col order-2 lg:order-4">
        <h3 class="text-3xl font-semibold">Total Answers</h3>
        <div class="text-6xl font-semibold flex-1 flex items-center justify-center">{{data.totalAnswers}}</div>
      </div>

      <!-- Latest surveys -->
      <div class="bg-white shadow-md p-4 row-span-2 order-3 lg:order-1">
        <h3 class="text-3xl font-semibold">latest Surveys</h3>
        <img :src="data.latestSurvey.image_url" alt="" class="w-[240px] mx-auto">
        <h3 class="font-bold text-xl mb-3">{{data.latestSurvey.title}}</h3>

        <div class="flex justify-between text-sm mb-1">
          <div>Create Date:</div>
          <div>{{data.latestSurvey.created_at}}</div>
        </div>

        <div class="flex justify-between text-sm mb-1">
          <div>Expire Date:</div>
          <div>{{data.latestSurvey.expire_date}}</div>
        </div>

        <div class="flex justify-between text-sm mb-1">
          <div>Status:</div>
          <div>{{data.latestSurvey.status ? 'Active' : 'Draft'}}</div>
        </div>

        <div class="flex justify-between text-sm mb-1">
          <div>Questions:</div>
          <div>{{data.latestSurvey.questions}}</div>
        </div>
        <div class="flex justify-between text-sm mb-1">
          <div>Answers:</div>
          <div>{{data.latestSurvey.answers}}</div>
        </div>

        <div class="flex justify-between">
          <router-link 
            :to="{name: 'SurveyView', params: {id: data.latestSurvey.id}}"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Edit Survey
          </router-link>

          <button class="flex">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            View Answers</button>
        </div>
      </div>
      <div class="bg-white shadow-md p-3 row-span-2 order-4 lg:order-2">
          <div class="flex justify-between items-center mb-3 px-2">
            <h3 class="text-2xl font-semibold">Latest Answers</h3>
            <a href="javascript:void(0)" class="text-sm text-blue-500 hover:decoration-blue-500">View all</a>
          </div>

          <a href="#" v-for="answer of data.latestAnswers"
            :key="answer.id"
          >
            <div class="font-semibold">{{answer.survey.title}}</div>
            <small class="font-semibold">{{answer.end_date}}<i></i></small>
          </a>
      </div>
    </div>
  </PageComponent>  
</template>

<script setup>
  import PageComponent from '../components/PageComponent.vue';

  import { computed } from 'vue';
  import { useStore } from 'vuex';

  const store = useStore();

  const loading = computed(() => store.state.dashboard.loading);
  const data = computed(() => store.state.dashboard.data);

  store.dispatch('getDashboardData'); // it will request for data and get data. after getting data, will update state 'dashoboard' and then it will computed (auto load) here {{data}}
</script>