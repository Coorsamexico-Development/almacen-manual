
<script setup>
import { computed } from "@vue/runtime-core";
const props = defineProps({
    id: {
        default: -1
    },
    itemSelect: {
        type: Object,
        default: {}
    }
})

const classes = computed(() => props.id === props.itemSelect.id ?
    'bg-gray-900 text-white'
    : 'border-gray-400 '
);
</script>
<template>
    <li :class="classes"
        class="inline-flex items-center justify-center w-full px-4 py-2 text-xs font-semibold uppercase transition border cursor-pointer hover:text-white hover:bg-gray-800 active:bg-gray-900 active:text-white focus:outline-none focus:border-gray-900 disabled:opacity-25">
        <slot></slot>
    </li>
</template>
