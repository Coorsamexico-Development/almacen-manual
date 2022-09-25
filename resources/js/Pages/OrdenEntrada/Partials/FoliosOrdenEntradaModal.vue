<script setup>
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import FoliosTable from './FoliosTable.vue';
import EntradasFolioTable from './EntradasFolioTable.vue';

const emit = defineEmits(['close', 'messageError', 'showImagesTarima']);
const folioSelected = ref({ id: -1 })


const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    ordenEntrada: {
        type: Object,
        required: true
    },
});



const close = () => {
    emit('close');
    folioSelected.value = { id: -1 }
};

const selectFolio = (folio) => {
    folioSelected.value = folio;
}

</script>


<template>
    <DialogModal :show="show" maxWidth="6xl" @close="close()">
        <template #title>
            <div class="flex gap-2 px-2">
                <h1 class="font-semibold text-gray-600 text-md">
                    Tarimas
                </h1>
                <span class="p-1 font-semibold text-blue-900">
                    {{ ordenEntrada.name }}
                </span>
            </div>
        </template>
        <template #content>
            <div class="flex w-full">
                <FoliosTable :class="folioSelected.id == -1 ? 'w-full' : 'w-1/2'" :orden-entrada="ordenEntrada"
                    @selected="selectFolio($event)" />
                <EntradasFolioTable v-if="folioSelected.id !== -1" :folio="folioSelected" />
            </div>
        </template>
    </DialogModal>
</template>
