<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center px-6 pt-4">
        <h1 class="text-2xl font-bold text-gray-900">Surveys</h1>
        
        <router-link :to="{name: 'SurveyCreate'}" class="lvs-primary-btn">
          Add new survey
        </router-link>
      </div>
    </template>

    <!-- Survey grids -->
    <div v-if="surveys.loading" class="flex justify-center min-h-screen">Loading...</div>
    <div v-else>
      <div v-if="surveys.data.length > 0" >
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 min-h-screen">
            <SurveyListItem 
              v-for="(survey, i) in surveys.data"
              :key="survey.id" 
              :survey="survey" 
              class="opacity-0 animate-fade-in-down" 
              :style="{ animationDelay: `${i * 0.1}s` }"
              @delete="deleteSurvey(survey)"
            />
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-5 ">
          <nav class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px" aria-label="Pagination">
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
      <div class="min-h-screen">
        <p class="text-2xl text-center">You not added a survey yet...</p>
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