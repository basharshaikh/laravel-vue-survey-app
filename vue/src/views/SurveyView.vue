<template>
    <PageComponent>
        <template v-slot:header>
        <div class="flex justify-between items-center px-6 pt-4">
            <h3 class="text-2xl font-bold text-gray-900">
                {{route.params.id ? model.title : "Create a survey"}}
            </h3>
            <button v-if="route.params.id" type="button" @click="deleteSurvey()" class="lvs-danger-btn">Delete</button>
        </div>
        </template>

        <div v-if="surveyLoading" class="flex justify-center">Loading...</div>

        <Alert v-if="Object.keys(errors).length" class="flex-col items-stretch text-sm">
            <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <div v-for="(error, ind) of errors[field] || []" :key="ind">
                    *{{error}}
                </div>
            </div>
        </Alert>
        
        <!-- form -->
        <form v-else>
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-4 sm:grid-cols-1 md:grid-cols-1">
                <div class="col-span-7 shadow sm:rounded-md sm:overflow-hidden">
                <!-- fields -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <!-- title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input
                        type="text"
                        name="title"
                        id="title"
                        v-model="model.title"
                        autocomplete="survey_title"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                    </div>
                    <!-- description -->
                    <div>
                        <label for="about" class="block text-sm font-medium text-gray-700">
                        Description
                        </label>
                        <div class="mt-1">
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            v-model="model.description"
                            autocomplete="survey_description"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Describe your survey"
                        />
                        </div>
                    </div>

                </div>

                <!-- Questions fields -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3 class="text-2x1 font-semibold flex items-center justify-between">
                        Questions

                        <button type="button" @click="addQuestion()" class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"
                                />
                            </svg>
                            Add Question
                        </button>
                    </h3>
                    <div v-if="!model.questions" class="text-center text-gray-600">
                        You don't have any questions created
                    </div>

                    <div v-for="(question, index) in model.questions" :key="question.id" class="">
                        <QuestionEditor
                            :question="question"
                            :index="index"
                            @change="questionChange"
                            @addQuestion="addQuestion"
                            @deleteQuestion="deleteQuestion"
                        ></QuestionEditor>
                    </div>
                </div>


                </div>

                <!-- Form right side -->
                <div class="col-span-3 shadow sm:rounded-md sm:overflow-hidden">
                    <div class="publish-box mb-4">
                        <h4 class="text-lg font-bold border-b-[1px] py-2 px-3">Publish</h4>
                        <div class="p-3 pb-0">
                            You can save as draft or publish it publicly. also delete it from here.
                        </div>
                        
                        <div class="p-3">
                            <!-- expire date -->
                            <div class="mb-3">
                                <label
                                for="expire_date"
                                class="block text-sm font-medium text-gray-700"
                                >Expire Date</label
                                >
                                <input
                                type="date"
                                name="expire_date"
                                id="expire_date"
                                v-model="model.expire_date"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <!-- status -->
                            <div class="mb-3 flex items-start">
                                <div class="flex items-center h-5">
                                <input
                                    id="status"
                                    name="status"
                                    type="checkbox"
                                    v-model="model.status"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                />
                                </div>
                                <div class="ml-3 text-sm">
                                <label for="status" class="font-medium text-gray-700"
                                    >Active</label
                                >
                                </div>
                            </div>
                            <div class="">
                                <button @click="saveSurvey" type="submit" class="lvs-primary-btn">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="publish-box mb-4">
                        <h4 class="text-lg font-bold border-b-[1px] py-2 px-3">Choose an image</h4>
                        <div class="p-3">
                            <div class="mt-1 flex items-center">
                                <img v-if="model.image_url" :src="model.image_url" :alt="model.title" class="w-64 h-48 object-cover"/>
                                <span v-else class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                    <PlaceholderImg />
                                </span>
                                <button type="button" class="lvs-media-btn">
                                    <input type="file"
                                        @change="onImageChoose"
                                        class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"
                                    />Change
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import PlaceholderImg from '../components/_icons/PlaceholderImg.vue';
import store from '../store'
import {ref, watch, computed} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import QuestionEditor from '../components/editor/QuestionEditor.vue';
import {v4 as uuidv4} from 'uuid'
import Alert from '../components/Alert.vue';


const route = useRoute();
const router = useRouter();

const surveyLoading = computed(()=>store.state.currentSurvey.loading);

// initial project model
let model = ref({
    title: '',
    slug: '',
    status: false,
    description: null,
    image: null,
    image_url: null,
    expire_date: null,
    questions: []
});

const errors = ref({});

// Saving or updating the servey
function saveSurvey(ev){
    ev.preventDefault()
    store.dispatch('saveSurvey', model.value)
    .then((res) => {
        // console.log(res.data.data);
        model.value = res.data.data
        store.commit('notify', {
            type: 'success',
            message: 'Survey data stored!'
        });
        if(!route.params.id){
            model.value = {}
        }
    })
    .catch((err) => {
      if(err.response.status === 422){
        errors.value = err.response.data.errors
      }
    })
}

// load edit survey
if(route.params.id){
    store.dispatch('getSurvey', route.params.id)
    .then((res) => {
        model.value = res.data.data
    });
}

// image choosing
function onImageChoose(ev){
    const file = ev.target.files[0];
    const reader = new FileReader();

    reader.onload = () => {
        model.value.image = reader.result;     
        model.value.image_url = reader.result;     
    }
    reader.readAsDataURL(file);
}

function addQuestion(index){
    const newQuestion = {
        id: uuidv4(),
        type: 'text',
        question: null,
        data: {}
    }
    model.value.questions.splice(index, 0, newQuestion)
    //https://www.w3schools.com/jsref/jsref_splice.asp pushing data in array
}

function deleteQuestion(question){
    model.value.questions = model.value.questions.filter(
        (q) => q !== question
    )

    //https://www.w3schools.com/jsref/jsref_filter.asp removing 
    // const ages = [32, 33, 16, 40];
    // const result = ages.filter(checkAdult);

    // function checkAdult(age) {
    //   return age >= 18;
    // }
}

function questionChange(question){
    model.value.questions = model.value.questions.map(
        (q) => {
            if(q.id === question.id){
                return JSON.parse(JSON.stringify(question))
            }

            return q
        }
    )

    // Updatation of array https://www.w3schools.com/jsref/jsref_map.asp
    // const numbers = [65, 44, 12, 4];
    // const newArr = numbers.map(myFunction)
    // function myFunction(num) {
    //   return num * 10;
    // }
}

// delete survey
function deleteSurvey(){
    if(confirm('Are you sure you want to delete this survey?')){
        store.dispatch('deleteSurvey', model.value.id)
        .then(() => {
            router.push({name: 'Surveys'})
        })
    }
}
</script>