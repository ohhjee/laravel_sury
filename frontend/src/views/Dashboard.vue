<template>
<PageComponents title="Dashboard">

  <!-- <pre>{{data}}</pre> -->
    <div v-if="loading" class="flex items-center justify-center">
        <button disabled type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
            <svg role="status" class="inline mr-3 w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB" />
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor" />
            </svg>
            Loading...
        </button>
    </div>

    <div v-else class="grid grid-cols-1 md:grid:cols-2 lg:grid-cols-3 gap-5 text-gray-700">
        <div class="bg-white shadow-md p-3 animate-fade-in-down text-center lg:order-2 flex flex-col order-1" style="animation-delay: 0.1s;">
            <h3 class="text-2xl font-semibold">Total Surveys</h3>
            <div class="text-8xl font-semibold flex-1 flex items-center justify-center">
                {{data.totalSurveys }}
            </div>
        </div>
        <div class="bg-white shadow-md animate-fade-in-down lg:order-4 p-3 text-center flex flex-col order-2" style="animation-delay: 0.2s;">
          <h3 class="text-2xl font-semibold">Total Answer</h3>
          <div class="text-8xl font-semibold flex-1 flex items-center justify-center">
              {{data.totalAnswer }}
          </div>
        </div>
        <div class="row-span-2 order-3 lg:order-1 animate-fade-in-down bg-white shadow-md p-4">
          <h3 class="text-2xl font-semibold">Latest Survey</h3>
          <img :src="data.latestSurvey.image_url" alt="" class="w-[240px] mx-auto"/>
          <h3 class="font-bold text-xl mb-3"> {{data.latestSurvey.title}} </h3>
          <div class="flex justify-between text-sm mb-1">
            <div>Create date:</div>
            <div>{{data.latestSurvey.created_at}}</div>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <div>Expire date:</div>
            <div>{{data.latestSurvey.expire_date}}</div>
          </div><div class="flex justify-between text-sm mb-1">
            <div>status</div>
            <div>{{data.latestSurvey.status ? 'Active' : 'Draft'}}</div>
          </div>
          <div class="flex justify-between">
           <router-link :to="{name:'SurveyView', params:{id:data.latestSurvey.id}}" class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 items-center gap-[.3rem]" >
            <PencilIcon class="w-4 h-4" />
            Edit Survey
           </router-link>
           <button class="flex items-center py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 gap-[.3rem]">
            <EyeIcon class="w-4 h-4"/>
            view Answers
           </button>
          </div>
          <div class="flex justify-between text-sm my-2">
            <div>Questions:</div>
            <div>{{data.latestSurvey.questions}}</div>
          </div>
          <div class="flex justify-between text-sm my-2">
            <div>Answers:</div>
            <div>{{data.latestSurvey.answers}}</div>
          </div>
        </div>
        <div class="row-span-2 order-4 lg:order-3 bg-white shadow-md animate-fade-in-down p-4" style="animation-delay: 0.3s;">
         <div class="flex items-center mb-3 px-2 justify-between">
          <h3 class="text-2xl font-semibold">Latest Answers</h3>
          <a href="javascript:void(0)" class="text-xs text-blue-500 hover:decoration-blue-500">
            view all
          </a>
         </div>
         <a href="#" 
         v-for="answer of data.latestAnswer"
         :key="answer.id"
         class="block p-2 hover:bg-gray-100/90"
         >
        <div class="font-semibold">
          {{answer.survey.title}}
        </div>
          <small>
            Answer made at:
            <i class="font-semibold">{{answer.end_date}}</i>
          </small>
        </a>
        </div>
    </div>
</PageComponents>
</template>

<script setup lang="ts">
import PageComponents from '../components/PageComponents.vue';
import { PencilIcon,EyeIcon } from '@heroicons/vue/24/outline';
import {
    computed
} from 'vue';
import {
    useStore
} from 'vuex';

const store = useStore();

const loading = computed(() => store.state.dashboard.loading)
const data = computed(() => store.state.dashboard.data)

store.dispatch('getDashboardData')
</script>

<style lang="scss">

</style>
