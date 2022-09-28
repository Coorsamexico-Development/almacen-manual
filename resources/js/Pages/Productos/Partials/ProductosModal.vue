<script setup>
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';

const emit = defineEmits(['close', 'messageError', 'showImagesTarima']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    producto: { 
        type:Object
    },
});



const close = () => {
    emit('close');
};


</script>


<template>
    <DialogModal :show="show" maxWidth="6xl" @close="close()">
        <template #title>
            <div class="flex gap-2 px-2">
                <h1 class="font-semibold text-gray-600 text-md">
                    Productos
                </h1>
                <span class="p-1 font-semibold text-blue-900">
                  
                </span>
            </div>
        </template>
        <template #content>
            <div class="flex w-full">

            </div>
        </template>
        <template #footer>
            <div class="mx-2 my-2">
                <SecondaryButton @click="close()">
                    Cancel
                </SecondaryButton>
            </div>
        </template>
    </DialogModal>
</template>
