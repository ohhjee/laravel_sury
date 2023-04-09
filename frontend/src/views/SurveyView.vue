<template>
<PageComponents>
    <template v-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">
                {{route.params.id ? model.title: 'Create a Survey'}}
            </h1>
            <button
            v-if="route.params.id"
            type="button"
class="py-2 px-3 text-white bg-red-500  flex items-center justify-center rounded-md hover:bg-red-600"
@click="deleteSurvey()"
            >
        <TrashIcon class="w-4 h-4 mr-2"/>
        Delete Survey
        </button>
        </div>
    </template>
    <!-- <pre>{{model}}</pre> -->

    <div v-if="surveyLoading" class="flex items-center justify-center">
        <button disabled type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
            <svg role="status" class="inline mr-3 w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
            </svg>
            Loading...
        </button>
    </div>
    <!-- <div class=""></div> -->
    <form v-else @submit.prevent="saveSurvey" class="animate-fade-in-down">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <!-- Survey field -->
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div>
                    <label for="" class="block text-sm  font-medium text-gray-700">
                        Image
                    </label>
                    <div class="mt-1 flex items-center">
                        <img class="w-64 h-48 object-cover" v-if="model.image_url" :src="model.image_url" :alt="model.title" />
                        <span v-else class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                            <PhotoIcon class="w-[80%] h-[80%] text-gray-300" />
                        </span>
                        <button type="button" class="relative overflow-hidden ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus-ring-offset-2 focus:ring-indigo-500">
                            <input type="file" @change="onImageChoose" name="" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer" id="">
                            change</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="title">
                        Title
                    </label>
                    <input type="text" name="title" id="title" v-model="model.title" autocapitalize="survey_title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3" v-model="model.description" class="mt-1 focus:ring-indigo-500 resize-none focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Describe your survey" />

                        </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="expire_date">
                    Expire Date
                </label>
                <input type="date" name="expire_date" id="expire_date" v-model="model.expire_date" autocapitalize="survey_title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" id="status" v-model="model.status" name="status" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 rounded border-gray-300">
                </div>
                <div class="ml-3 text-sm">
                    <label for="status" class="font-medium text-gray-700">Active</label>
                </div>
            </div>
        </div>
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
            <h3 class="text-2xl font-semibold flex items-center justify-between">
                Questions
                <button type="button" @click="addQuestion()" class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-500 hover:bg-gray-700">
                    <PlusIcon class="h-4 w-4"/>
                    Add Questions
                </button>
            </h3>
            <div class="text-center text-gray-600" v-if="!model.questions?.length">
                You don't have any questions created.
            </div>
            <div v-for="(question, index) in model.questions" :key="question.id">
                <QuestionEditor
                :question="question"
                :index="index"
                @change="questionChange"
                @addQuestion="addQuestion"
                @deleteQuestion="deleteQuestion"
                />
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus::outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">save</button>
        </div>
    </div>
   </form>
</PageComponents>
</template>

<script setup lang="ts">
import {
    ref, watch,computed
} from 'vue';
import store from '../store';
import {
    useRoute,
    useRouter
} from 'vue-router';
import {
    PhotoIcon,
    PlusIcon,
    TrashIcon
} from '@heroicons/vue/24/outline'
import PageComponents from '../components/PageComponents.vue';
import QuestionEditor from '../components/editor/QuestionEditor.vue';
import {
    v4 as uuidv4
} from 'uuid'
const route = useRoute();

const router = useRouter()

const surveyLoading = computed(()=> store.state.currentSurvey.loading)


let model = ref({
    title: '',
    status: false as boolean,
    description: null as any,
    expire_date: null,
    image_url: null as any,
    image:null as any ,
    questions: [] as any[]
}) as any
if (route.params.id) {
   store.dispatch('getSurvey', route.params.id);
}



watch(()=>store.state.currentSurvey.data, (newVal, oldVal)=>{
    model.value={
        ...JSON.parse(JSON.stringify(newVal)),
        status:newVal.status !== 'draft'
    }
}

)


function onImageChoose(ev) {
    const file = ev.target.files[0]

    const reader = new FileReader();
    reader.onload = () => {
        model.value.image = reader.result
        model.value.image_url = reader.result
    }
    reader.readAsDataURL(file)
}

function addQuestion(index):any {
    const newQuestion = {
        id: uuidv4(),
        type: 'text',
        question: "",
        description: null,
        data: {},
    } as any
    model.value.questions?.splice(index, 0, newQuestion)
}

function deleteQuestion(question) {
    model.value.questions = model.value.questions.filter((q) => q !== question)
}

function questionChange(question) {
    model.value.questions = model.value.questions.map((q) => {
        if (q.id === question.id) {
            return JSON.parse(JSON.stringify(question))
        }
        return q;
    })
}

function saveSurvey() {
    store.dispatch('saveSurvey', model.value).then(({data}) => {
        store.commit('notify',{
type:'success',
message:'Survey was successfully updated'
})
        router.push({
            name: 'SurveyView',
            params: {
                id: data.data.id
            },
        })
    })
}
function deleteSurvey(){
    if (confirm('Are you sure you want to delete')) {

    }
    store.dispatch('deleteSurvey', model.value.id).then((res)=>{
        router.push({
            name:"Surveys"
        })
    })
}
</script>

<style lang="scss">

</style>
