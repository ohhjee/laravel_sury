<template>
    <div class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px]">
        <img :src="survey.image_url" class="w-full h-48 object-cover" alt="">
        <h4 class="mt-4 text-lg font-bold">{{survey.title}}</h4>
        <div  class="overflow-hidden flex-1" v-html="survey.description"></div>
        <div class="flex justify-between items-center mt-3">
            <router-link :to="{name:'SurveyView', params:{id:survey.id}}" class="flex py-2 px-4 border border-transparent text-sm rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <PencilIcon class="w-5 h-5 mr-2 flex items-center"/>
                Edit
            </router-link>
          <div class="flex items-center">
            <a :href="`/view/survey/${survey.slug}`" class="h-8 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-indigo-500 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                  </svg>

            </a>
            <button v-if="survey.id" @click="emit('delete', survey )" type="button" class="h-8 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-red-500 focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <TrashIcon class="w-5 h-5 -mt-1 inline-block"/>
            </button>
          </div>
        </div>
    </div>
</template>
<script setup lang="ts">
    import {
    PlusIcon,
    PencilIcon,
    TrashIcon
} from '@heroicons/vue/24/outline';
const {survey} :string | any = defineProps({
    survey: Object
})

const emit  = defineEmits(['delete'])
</script>
<style lang="scss"></style>
