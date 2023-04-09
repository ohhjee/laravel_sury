<template>
<PageComponents>
    <template v-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
            <router-link :to="{name:'SurveyCreate'}" class="px-3 py-2 text-white bg-emerald-500 rounded-md hover:bg-emerald-600">
                <PlusIcon class="w-4 h-4 -mt-1 inline-block text-white" />
                Add new Survey
            </router-link>
        </div>
    </template>
    <div v-if="surveys.loading" class="flex items-center justify-center">
        <button disabled type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
            <svg role="status" class="inline mr-3 w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
            </svg>
            Loading...
        </button>
    </div>
    <div v-else>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
            <SurveyListen v-for="(survey, ind) in surveys.data" :key="survey.id"  :survey="survey" class="opacity-0 animate-fade-in-down" :style="{animationDelay: `${ind * .1}s`}" @delete="deleteSurvey(survey)"/>
        </div>


        <div class="flex justify-center mt-5">
            <nav class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px">
                <a v-for="(link, i) in surveys.links"
                :key="i"
                :disabled="!link.url"
                v-html="link.label"
                href="#"
                @click="getForPage(link)"
                aria-current="page"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
                :class="[link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md' : '',
                i === surveys.links.length - 1 ? 'rounded-r-md' : '',
                ]"
                ></a>
            </nav>
        </div>
    </div>
</PageComponents>
</template>

<script setup lang="ts">
import PageComponents from '../components/PageComponents.vue';
import store from '../store';
import { computed } from 'vue';
import { PlusIcon } from '@heroicons/vue/24/outline';
import SurveyListen from '../components/surveyListen.vue';
const surveys = computed(()=>store.state.surveys) as any

store.dispatch("getSurveys")

function deleteSurvey(survey) {
    if(confirm(`Are you sure you want to delete this survey, Operation can't be undone!!`)){

    }{

        store.dispatch('deleteSurvey', survey.id).then(()=>{
            store.dispatch('getSurveys')
        })
    }
}

function getForPage(link) {
    if(!link.url || link.active){
        return;
    }
    store.dispatch("getSurveys",{url:link.url})
}
</script>

<style lang="scss">

</style>
