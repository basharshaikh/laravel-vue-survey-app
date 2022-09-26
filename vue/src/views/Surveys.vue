<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
        <router-link
        :to="{name: 'SurveyCreate'}"
        class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block -mt-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>

          Add new survey
        </router-link>
      </div>

      
    </template>

    <!-- Survey grids -->
    <div v-if="surveys.loading" class="flex justify-center">Loading...</div>
    <div v-else >
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
          <SurveyListItem 
            v-for="(survey, i) in surveys.data"
            :key="survey.id" 
            :survey="survey" 
            class="opacity-0 animate-fade-in-down" 
            :style="{ animationDelay: `${i * 0.1}s` }"
            @delete="deleteSurvey(survey)"
          />
      </div>

      <div class="flex justify-center mt-5">
        <nav
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
          <a
            v-for="(link, i) of surveys.links"
            :key="i"
            :disabled="!link.url" 
            href="#"
            @click="getForPage($event, link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',

              i === 0 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',

              i === surveys.links.length - 1 ? 'rounded-r-md' : '',
            ]"
            v-html="link.label"
          >
          </a>
        </nav>
      </div>
    </div>
  </PageComponent>  
</template>

<script setup>
  import PageComponent from '../components/PageComponent.vue';
  import store from '../store';
  import { computed } from 'vue';
  import SurveyListItem from '../components/SurveyListItem.vue';

  const surveys = computed(() => store.state.surveys);

  store.dispatch('getSurveys')

  function getForPage(ev, link) {
    ev.preventDefault();
    if (!link.url || link.active) {
      return;
    }

    store.dispatch("getSurveys", { url: link.url });
  }

  function deleteSurvey(survey){
    if(confirm(`Are you sure you want to delete?`)){
      store.dispatch('deleteSurvey', survey.id)
      .then(()=>{
        store.dispatch('getSurveys')
      })
    }
  }
</script>