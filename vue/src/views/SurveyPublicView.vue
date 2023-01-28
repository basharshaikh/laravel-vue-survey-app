<template>
    <div class="container max-w-max m-auto my-10">
        <div v-if="loading" class="flex justify-center">Loading...</div>
        <form @submit.prevent="submitSurvey" v-else class="">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-4 sm:grid-cols-1 md:grid-cols-1">
                <div class="col-span-3 shadow sm:rounded-md sm:overflow-hidden p-6">
                    <div class="text-center">
                        <div class="">
                            <img class="rounded-md m-auto mb-3" width="200" :src="survey.image_url" alt="">
                        </div>

                        <div class="col-span-5">
                            <h1 class="text-xl font-bold">{{survey.title}}</h1>
                            <p class="text-gray-500 text-sm" v-html="survey.description"></p>
                        </div>
                    </div>
                </div>

                <!-- Right side survey ans collect -->
                <div class="col-span-7 shadow sm:rounded-md sm:overflow-hidden p-8">
                    <div v-if="surveyFinished" class="py-8 px-6 bg-emerald-400 text-white w-[600px] mx-auto">
                        <div class="text-xl mb-3 fornt-semibold">
                            Thank you for participating in our survey
                        </div>
                        <button 
                            @click="submitAnotherResponse"
                            type="button"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >Submit another response</button>
                    </div>
                    <div v-else class="col-span-5">
                        <div class="flex justify-between">
                            <h3 class="text-lg font-bold">Fill the form with your opinion</h3>
                            <button type="submit" class="lvs-primary-btn">Submit</button>
                        </div>
                        <hr class="my-3">
                        <div v-for="(question, ind) of survey.questions" :key="question.id">
                            <QuestionViewer
                                v-model="answers[question.id]"
                                :question="question"
                                :index="ind"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>


<script setup>
import QuestionViewer from '../components/viewer/QuestionViewer.vue';
import { computed, ref } from 'vue';
import { useRoute } from "vue-router";
import { useStore } from "vuex";

const route = useRoute();
const store = useStore();

const loading = computed(() => store.state.currentSurvey.loading);
const survey = computed(() => store.state.currentSurvey.data);

const surveyFinished = ref(false);

const answers = ref({});

store.dispatch('getSurveyBySlug', route.params.slug);

function submitSurvey(){
    console.log(JSON.stringify(answers.value, undefined, 2));

    store.dispatch('saveSurveyAnswer', {
        surveyId: survey.value.id,
        answers: answers.value,
    })
    .then((response) => {
        if(response.status === 201){ // success saved
            surveyFinished.value = true
        }
    })
}


// reseting the survey and forms
function submitAnotherResponse(){
    answers.value = {};
    surveyFinished.value = false;
}
</script>