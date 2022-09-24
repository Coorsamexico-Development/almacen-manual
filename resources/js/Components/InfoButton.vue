<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type"
        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-blue-500 border border-transparent rounded-md hover:bg-blue-400 active:bg-blue-600 focus:outline-none focus:border-blue-600 focus:ring focus:ring-blue-300 disabled:opacity-25">
        <slot />
    </button>
</template>
