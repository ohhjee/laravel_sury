<template>
<fieldset class="mb-4">
    <div>
        <legend class="text-base font-medium text-gray-900">
            {{index + 1}}. {{question.question}}
        </legend>
        <p class="text-gray-500 text-sm">
            {{question.description}}
        </p>
    </div>
    <div class="mt-3">
        <div v-if="question.type === 'select'">
            <select :value="modelValue" @click="emits('update:modelValue', selects.value)" class="mt-1 block w-full py-2 px-3 border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Please select</option>
                <option v-for="option in question.data.options" :key="option.uuid" :value="option.text">
                    {{option.text}}
                </option>
            </select>
        </div>
        <div v-else-if="question.type === 'radio' ">
            <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center" >

                <input type="radio" :id="option.uuid" :name="'question' + question.id" :value="option.text" @change="emits('update:modelValue', selects.value)" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label :for="option.uuid" class="ml-3 block tet-sm font-medium text-gray-700">
                    {{option.text}}
                </label>
            </div>
        </div>
         <div v-else-if="question.type === 'checkbox' ">
            <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center" >

                <input type="checkbox" :id="option.uuid" v-model="model[option.text]" @change="onCheckBoxChange" :name="'question' + question.id" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label :for="option.uuid" class="ml-3 block tet-sm font-medium text-gray-700">
                    {{option.text}}
                </label>
            </div>
        </div>
         <div v-else-if="question.type === 'text' ">
            <input type="text" :value="modelValue" @change="emits('update:modelValue', selects.value)" class="focus:ring-indigo-500 text-indigo-600 w-full border-gray-300">
        </div>
         <div v-else-if="question.type === 'textarea' ">
            <textarea :value="modelValue" @change="emits('update:modelValue',selects.value)" class="focus:ring-indigo-500 resize-none w-full text-indigo-600 border-gray-300"/>
        </div>
    </div>
</fieldset>
<hr class="mb-4">
</template>

<script setup lang="ts">
import {
    ref
} from 'vue';
const {
    question,
    index,
    modelValue
} = defineProps({
    question: Object as any,
    index: Number as any,
    modelValue: [String, Array] as any,
})
const emits = defineEmits(["update:modelValue"])

let model;

const selects = event?.target as any as unknown

if (question.type === 'checkbox') {
    model = ref({})
}

function onCheckBoxChange($event) {
    const selectedOptions: any[] = [];
    for (let text  in model.value) {
        if(model.value[text]){
            selectedOptions.push(text)
        }
    }
    emits('update:modelValue', selectedOptions)
}
</script>
