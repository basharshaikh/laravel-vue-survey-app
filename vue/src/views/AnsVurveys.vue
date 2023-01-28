<template>
    <PageComponent>
        <template v-slot:header>
        <div class="flex justify-between items-center px-6 pt-4">
            <h3 class="text-2xl font-bold text-gray-900">
                Survey Answers
            </h3>
        </div>
        
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-10 gap-4 sm:grid-cols-1 md:grid-cols-1">
            <div class="col-span-3 shadow-lg rounded-md">
                <img class="mt-6 mb-2 rounded-md" v-if="survey.image_url" :src="survey.image_url" :alt="survey.title">
                <div class="p-4 text-center">
                    <h2 class="font-semibold text-xl">{{ survey.title }}</h2>
                    <p>{{ survey.description }}</p>
                </div>
                
            </div>

            <div class="col-span-7 shadow-md p-6">
                <div class="grid grid-cols-1 lg:grid-cols-10 gap-4 sm:grid-cols-1 md:grid-cols-1">
                    <div v-for="(ans, i) in survey.answers" class="border mb-4 p-4 col-span-5">
                        <h3 class="font-semibold text-xl mb-3">{{ i+1 }}. Survey answer</h3>

                        <div v-for="(qa, index) in ans.answers" class="my-3">      
                            <hr class="mb-3">
                            <div v-for="q in survey.questions" class="">
                                <div class="font-semibold" v-if="q.id == qa.survey_question_id">Q: {{ q.question }}</div>
                            </div>
                            <p>Ans: {{ qa.answer }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import store from '../store';
import {ref} from 'vue'
import { useRoute } from "vue-router";
const route = useRoute();


const survey = ref({})
store.dispatch("geSingleSurveyWithAns", route.params.id)
.then((res) => {
    survey.value = res.data.data
})

</script>