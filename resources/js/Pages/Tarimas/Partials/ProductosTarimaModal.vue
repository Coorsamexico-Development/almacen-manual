<script setup>
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import ProductosTarimaTable from './ProductosTarimaTable.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';


const emit = defineEmits(['close', 'messageError', 'showImagesTarima']);
const productoSelected = ref({ id: -1 })


const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    tarima: {
        type: Object,
        require: true
    },
});



const close = () => {
    productoSelected.value = { id: -1 }
    emit('close');
};

const selectTarima = (folio) => {
    productoSelected.value = folio;
}

</script>


<template>
    <DialogModal :show="show" maxWidth="6xl" @close="close()">
        <template #title>
            <div class="flex gap-2 px-2">
                <h1 class="font-semibold text-gray-600 text-md">
                    Productos
                </h1>
                <span class="px-2 text-white bg-gray-700 rounded">
                    {{tarima.name}}
                </span>
            </div>
        </template>
        <template #content>
            <div class="flex w-full">
                <ProductosTarimaTable :class="productoSelected.id == -1 ? 'w-full' : 'w-1/2'" :tarima="tarima"
                    @selected="selectTarima($event)" />
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
