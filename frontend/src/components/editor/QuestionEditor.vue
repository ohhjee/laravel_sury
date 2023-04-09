<template>
<div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">
        {{index + 1}}. {{model.question}}
    </h3>
    <div class="flex items-center">
        <button type="button" class="flex items-center text-xs py-1 px-3 mr-2 rounded-sm text-white bg-gray-600 hover:bg-gray-700" @click="addQuestion()">
            <PlusIcon class="w-4 h-4" />Add</button>
        <button type="button" class="flex items-center text-xs py-1 px-3  rounded-sm  text-red-600 border border-transparent hover:border-red-600" @click="deleteQuestion()">
            <TrashIcon class="w-4 h-4" /> Delete</button>
    </div>
</div>
<div class="grid gap-3 grid-cols-12">
    <div class="mt-3 col-span-9">
        <label :for="'question_text_' + model.data" class="block text-sm font-medium text-gray-700">
            Question Text
        </label>
        <input type="text" :name="'question_text_' + model.data" v-model="model.question" :id="'question_text_' + model.data" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" @change="dataChange">
    </div>
    <div class="mt-3 col-span-3">
        <label for="question_type" class="block text-sm font-medium text-gray-700 ">
            Select Question Type
        </label>
        <select v-model="model.type" @change="typeChange" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="question_type" id="question_type">
            <option v-for="type in questionTypes" :key="type" :value="type" >
                {{upperCaseFirst(type)}}
            </option>
        </select>
    </div>
</div>
<div class="mt-3 col-span-9">
    <label class="block text-sm font-medium text-gray-700" :for="'question_description_' + model.id">
        Description
    </label>
    <textarea :id="'question_description_' + model.id" :name="'question_description_' + model.id" v-model="model.description" @change="dataChange" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-300 rounded-md sm:text-sm w-full block resize-none" />
</div>

<div>
    <div v-if="ShouldHaveOptions()" class="mt-2">
        <h4 class="text-sm font-semibold mb-1 flex justify-between items-center">
            Options
            <button type="button" @click="addOption()" class="flex items-center text-xs py-1 px-2 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
                <PlusIcon class="w-4 h-4"/>
                Add Option
            </button>
        </h4>
        <div v-if="!model.data.options.length" class="text-ts text-gray-600 text-center py-3">
            You don't have any options define
        </div>
        <div class="flex items-center mb-1" v-for="(option,index) in model.data.options" :key="option.uuid">
            <span class="w-6 text-sm"> {{index + 1}}.</span>
            <input type="text"
            v-model="option.text"
            @change="dataChange"
            class="w-full rounded-sm py-1 px-2 text-xs border border-gray-300 focus:border-indigo-500"
            >

            <button type="button" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100" @click="removeOption(option)">
                <TrashIcon class="w-4 h-4 text-red-500"/>
            </button>
        </div>
    </div>
</div>
<hr class="my-4">
</template>

<script lang="ts" setup>
import {
    ref,computed
} from 'vue';
import store from '../../store'
import {v4 as uuidv4} from 'uuid'
import {
    PlusIcon,
    TrashIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    question: Object,
    index: Number as number | any, 

})
const emits = defineEmits(['change', 'addQuestion', 'deleteQuestion']);

const model = ref(JSON.parse(JSON.stringify(props.question)));


const questionTypes=computed(()=>store.state.questionTypes)

function upperCaseFirst(str) {
     return str.charAt(0).toUpperCase() + str.slice(1)
}
function ShouldHaveOptions() {
    return ["select", "radio", "checkbox"].includes(model.value.type)
}
function getOptions() {
    return model.value.data.options
}function setOptions(options) {
    model.value.data.options = options
}
function addOption() {
    setOptions([
        ...getOptions(),
        {uuid: uuidv4(), text:""}
    ]);
    dataChange()
}
function removeOption(op) {
    setOptions(getOptions().filter((ops)=>ops !==op))
    dataChange()
}

function typeChange() {
    if (ShouldHaveOptions()) {
        setOptions(getOptions() || []);
    }
    dataChange()
}
function dataChange() {
    const data = JSON.parse(JSON.stringify(model.value))
    if (!ShouldHaveOptions()) {
        delete data.data.options
    }
    emits('change', data)
}

function addQuestion() {
    emits('addQuestion', props.index + 1)
}
function deleteQuestion() {
    emits('deleteQuestion', props.question)
}
</script>

<style lang="scss"></style>
