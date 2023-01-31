<template>
    <PageComponent>
        <template v-slot:header>
        <div class="flex justify-between items-center px-6 pt-4">
            <h3 class="text-2xl font-bold text-gray-900">
                Survey Answers
            </h3>
        </div>
        
        </template>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
            <div v-for="(survey, i) in surveys" class="bg-white shadow-md p-4 ">
                <div class="">
                    <img class="" :src="baseUrl+'/'+survey.image">
                </div>
                <h3 class="font-bold text-xl my-3">{{survey.title}}</h3>
                <hr>

                <div class="flex justify-between text-sm mt-3">
                    <div>Create Date:</div>
                    <div>{{survey.created_at}}</div>
                </div>

                <div class="flex justify-between text-sm mb-1">
                    <div>Expire Date:</div>
                    <div>{{survey.expire_date}}</div>
                </div>

                <div class="flex justify-between text-sm mb-1">
                    <div>Status:</div>
                    <div>{{survey.status ? 'Active' : 'Draft'}}</div>
                </div>

                <div class="flex justify-between text-sm mb-1">
                    <div>Questions:</div>
                    <div>{{survey.questions.length}}</div>
                </div>

                <div class="flex justify-between text-sm mb-1">
                    <div>Answers:</div>
                    <div>{{survey.answers.length}}</div>
                </div>

                <div class="flex justify-between mt-3">
                    <router-link :to="{name: 'SurveyView', params: {id: survey.id}}" class="lvs-primary-btn"><PencilAltIcon class="w-6" /></router-link>

                    <router-link :to="`/survey-answers/${survey.id}`" class="lvs-primary-btn !bg-green-700">
                        <div class="flex">
                            <EyeIcon class="w-6"/>
                            <span class="ml-1">View Answers</span>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import store from '../store';
import {ref} from 'vue'
import { EyeIcon, PencilAltIcon } from '@heroicons/vue/solid';

const surveys = ref({})
const baseUrl = import.meta.env.VITE_API_BASE_URL
store.dispatch("geAllSurveyWithAns")
.then((res) => {
    surveys.value = res.data.data
})
</script>