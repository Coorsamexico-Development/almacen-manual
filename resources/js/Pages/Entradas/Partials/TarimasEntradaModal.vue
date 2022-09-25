import DialogModal from '@/Components/DialogModal.vue';
<script setup>
import { computed } from "@vue/runtime-core";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from "../../../Components/SpinProgress.vue";
import DialogModal from "@/Components/DialogModal.vue";

const emit = defineEmits(["close", "messageError"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    entrada: {
        type: Object,
        require: true
    },
});



const close = () => {
    emit('close');
}



</script>

<template>
    <DialogModal :show="show" maxWidth="4xl" @close="close()">
        <template #title>
            <h2 class="font-semibold text-md">
                Lista de Tarimas
                <span class="px-2 text-white bg-gray-700 rounded">
                    Folio: {{entrada.folio}}
                </span>
                <span class="px-2 ml-2 text-white bg-blue-600 rounded">
                    Ean: {{entrada.ean}}
                </span>
            </h2>
        </template>
        <template #content>

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
