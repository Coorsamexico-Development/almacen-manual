<script setup>
import { computed, onBeforeMount, reactive, ref, watch, watchEffect } from 'vue';
import ItemAction from './ItemAction.vue'
import InputGroup from './InputGroup.vue'
import SpinProgress from './SpinProgress.vue'


const emit = defineEmits(['createSeccion', 'getSeccion'])

const props = defineProps({
    secciones: {
        type: Array,
        required: true
    },
    padre: {
        type: Number,
        required: false
    },
    typeInput: {
        type: String,
        required: false,
        default: 'text',
    },
});


const form = reactive({
    cantidad: '',
    tipo: props.typeInput,
    error: '',
    recentlySuccessful: false,
    padre_id: props.padre,
    hasErrors: false,
    processing: false,
});
const seccionSelect = ref({});
const placeholder = ref('Nombre');

const createSeccion = () => {
    emit('createSeccion', form);
};
const selectSeccion = (seccion) => {
    seccionSelect.value = seccion;
    emit('getSeccion', seccion);
}

const buttonText = computed(() => {
    if (props.typeInput === 'text') {
        placeholder.value = 'Nombre'
        return 'Añadir';
    } else {
        placeholder.value = 'Cantidad a añadir'
        return `<svg xmlns="http://www.w3.org/2000/svg"
        fill="currentColor" class="w-6 h-6" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>`
    }
});

watchEffect(() => {

    form.padre_id = props.padre
});


</script>
<template>
    <div class="p-1 border">
        <slot></slot>
        <div class="flex justify-center mx-2 mt-2">
            <InputGroup :placeholder="placeholder" :type="typeInput" v-model="form.cantidad"
                @clickButton="createSeccion()">
                <template #button>
                    <SpinProgress :inprogress="form.processing"></SpinProgress>
                    <div v-html="buttonText"></div>
                </template>
            </InputGroup>
        </div>
        <div v-if="form.hasErrors" class="text-red-500">
            {{form.error}}
        </div>
        <ul v-if="secciones.length > 0" class="overflow-y-auto border" style="max-height:35vh">
            <ItemAction v-for="seccion in secciones" :itemSelect="seccionSelect" @click="selectSeccion(seccion)"
                :id="seccion.id" :key="seccion.id">
                {{seccion.name}}

            </ItemAction>
        </ul>

    </div>
</template>

