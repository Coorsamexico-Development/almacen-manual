<script setup>
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String,
    placeholder: {
        require: false,
        default: ''
    },
    disabled: {
        require: false,
        default: false
    },
    classButton: {
        require: false,
    },
    type: {
        require: false,
        default: 'text'
    }
})
defineEmits(['update:modelValue', 'clickButton']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

const buttonClasses = computed(() => {
    return props.classButton === undefined
        ? 'text-md  hover:bg-gray-800 hover:text-white' :
        props.classButton;
});


</script>
<template>
    <div class="relative flex flex-wrap items-stretch w-full mb-4">
        <input :type="type"
            class="relative flex-1 flex-grow flex-shrink w-px h-10 px-3 leading-normal border border-gray-500 rounded rounded-r-none"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input"
            :placeholder="placeholder" :disabled="disabled">
        <div class="flex -mr-px">
            <button @click="$emit('clickButton')" :class="buttonClasses"
                class="flex items-center px-3 leading-normal whitespace-no-wrap border border-l-0 border-gray-500 rounded rounded-l-none">
                <slot name="button"></slot>
            </button>
        </div>
    </div>
</template>
