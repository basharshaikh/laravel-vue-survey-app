<template>
  <PageComponent title="Dashboard">
    <div v-if="loading" class="flex justify-center relative min-h-screen">Loading...</div>
    <div v-else class="min-h-screen">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700">
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
        <h3 class="text-2xl font-bold mb-4 ">latest Survey</h3>
        <div class="bg-gray-200 p-4 rounded-md">
          <img class="w-60 rounded-md m-auto" :src="data.latestSurvey.image_url">
        </div>
        <h3 class="font-bold text-xl my-3">{{data.latestSurvey.title}}</h3>
        <hr>
        <div class="flex justify-between text-sm mt-3">
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

        <div class="flex justify-between mt-3">
          <router-link 
            :to="{name: 'SurveyView', params: {id: data.latestSurvey.id}}"
            class="lvs-primary-btn"
          >
            <PencilAltIcon class="w-6" title="Edit Survey"/>
          </router-link>

          <router-link 
            :to="`/survey-answers/${data.latestSurvey.id}`"
            class="lvs-primary-btn !bg-green-600"
          >
            <div class="flex">
              <EyeIcon class="w-6" title="Edit Survey"/>
              <span class="ml-1">Answers</span>
            </div>
          </router-link>

        </div>
      </div>

      <!-- Latest ans -->
      <div class="bg-white shadow-md p-3 row-span-2 order-4 lg:order-2">
          <div class="flex justify-between items-center mb-3 px-2">
            <h3 class="text-2xl font-bold">Latest Answers</h3>
            <a href="javascript:void(0)" class="text-sm text-blue-500 hover:decoration-blue-500">View all</a>
          </div>
          <hr>
          <div class="px-2">
              <a href="#" v-for="answer of data.latestAnswers" :key="answer.id">
                <div class="font-semibold">{{answer.survey.title}}</div>
                <small class="font-semibold">{{answer.end_date}}<i></i></small>
              </a>
          </div>

      </div>
    </div>
    </div>
  </PageComponent>  
</template>

<script setup>
  import PageComponent from '../components/PageComponent.vue';
  import { EyeIcon, FolderOpenIcon, PencilAltIcon } from '@heroicons/vue/solid';

  import { computed, ref } from 'vue';
  import { useStore } from 'vuex';

  const store = useStore();

  const loading = computed(() => store.state.dashboard.loading);
  const data = computed(() => store.state.dashboard.data);

  store.dispatch('getDashboardData'); // it will request for data and get data. after getting data, will update state 'dashoboard' and then it will computed (auto load) here {{data}}
</script>