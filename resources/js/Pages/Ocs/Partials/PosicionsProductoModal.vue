<script setup>
import { reactive, ref, watch, watchEffect } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from '../../../Components/DataTable.vue';
import SearchInput from '../../../Components/SearchInput.vue';
import PaginationAxios from '../../../Components/PaginationAxios.vue';
import { pickBy } from 'lodash';
import InputGroup from '../../../Components/InputGroup.vue';
import InputError from '../../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Components/InputError.vue';
import SpinProgress from '../../../Components/SpinProgress.vue';

const emit = defineEmits(['close', 'messageError', 'showImagesTarima']);


const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    salida: {
        type: Object,
        required: true
    },
});


const posicions = ref({ data: [] });
const filters = reactive({ search: null, field: null, direction: null, producto_id: -1, });





const searchPosiciones = async (params) => {

    try {
        const response = await axios.get(route('posicions-producto.index'), {
            params: params
        });
        posicions.value = response.data.posicions;
    } catch (error) {
        if (error.response) {
            console.log(error.response);
            let messageError = '';
            const messageServer = error.response.data.message
            if (error.response.status != 500) {
                messageError = messageServer;
            } else {
                messageError = 'Internal Server Error';
            }
            alert(messageError);
        }
    }
}

const loadPage = async (page) => {
    try {
        const response = await axios.get(route('posicions-producto.index'), {
            params: {
                producto_id: props.producto_id,
                search: filters.search,
                field: filters.field,
                direction: filters.direction,
                page: page
            }
        });
        posicions.value = response.data.posicions;
    } catch (error) {
        if (error.response) {
            console.log(error.response);
            let messageError = '';
            const messageServer = error.response.data.message
            if (error.response.status != 500) {
                messageError = messageServer;
            } else {
                messageError = 'Internal Server Error';
            }
            alert(messageError);
        }
    }
}
const updateSurtido = async (posicion) => {

    const newCantidad = parseInt(posicion.new_cantidad)
    if ((parseInt(props.salida.surtido) + newCantidad) > parseInt(props.salida.solicitado)) {
        posicion.error = 'La cantidad supera a lo solicitado';
        return;
    }
    if (posicion.new_cantidad > posicion.cant_disponible) {
        posicion.error = 'La cantidad supera a lo disponible';
        return;
    }
    try {
        posicion.processing = true;
        posicion.error = '';
        const response = await axios.put(route('salida.update', props.salida.id), {
            posicon_id: posicion.id,
            tarima_id: posicion.tarima_id,
            cantidad: newCantidad,
        });
        posicion.cant_disponible = parseInt(posicion.cant_disponible) - newCantidad;
        props.salida.surtido = parseInt(props.salida.surtido) + newCantidad;
        posicion.processing = false;
        posicion.new_cantidad = '';
    } catch (error) {
        posicion.processing = false;
        console.log(error);
        if (error.response) {
            let messageError = '';
            const messageServer = error.response.data.message
            if (error.response.status != 500) {
                messageError = messageServer;
            } else {
                messageError = 'Internal Server Error';
            }
            posicion.error = messageError;
        }
    }
}
const sort = (field) => {
    filters.field = field;
    filters.direction = filters.direction === "asc" ? "desc" : "asc";
}



watchEffect(() => {
    if (props.salida.id !== -1 && props.show) {
        filters.producto_id = props.salida.producto_id;
    } else {
        filters.producto_id = -1;
    }
})
let timeout;
watch(filters, function () {
    if (props.show) {
        if (timeout !== undefined) {
            clearTimeout(timeout)
        }
        timeout = setTimeout(() => {
            searchPosiciones(pickBy(filters));
        }, 300)
    }
})



const close = () => {
    emit('close');
};


</script>


<template>
    <DialogModal :show="show" @close="close()">
        <template #title>
            <div class="flex gap-2 px-2">
                <h1 class="font-semibold text-gray-600 text-md">
                    POSICIONES SKUS
                </h1>
                <span class="px-2 text-white bg-gray-700 rounded">
                    SKU:{{ salida.ean }}
                </span>
                <span class="px-2 text-white bg-blue-500 rounded">
                    Avance:{{ salida.surtido }}/{{salida.solicitado}}
                </span>
            </div>
        </template>
        <template #content>
            <div class="sm:rounded-lg">
                <DataTable>
                    <template #section-header>
                        <div>
                            <SearchInput v-model="filters.search" />
                        </div>

                    </template>
                    <template #table-header>
                        <tr class="text-center">
                            <th scope="col"
                                class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                <span class="" @click="sort('posicions.name')">
                                    POSICION
                                    <template v-if="filters.field === 'posicions.name'">
                                        <svg v-if="filters.direction === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                                        </svg>
                                        <svg v-if="filters.direction === 'desc'" xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    </template>
                                </span>
                            </th>
                            <th scope="col"
                                class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                <span class="" @click="sort('cant_disponible')">
                                    CANTIDAD DISPONIBLE
                                    <template v-if="filters.field === 'cant_disponible'">
                                        <svg v-if="filters.direction === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                                        </svg>
                                        <svg v-if="filters.direction === 'desc'" xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    </template>
                                </span>
                            </th>
                            <th scope="col"
                                class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                TOMAR
                            </th>
                        </tr>
                    </template>
                    <template #table-body>
                        <tr v-for="(posicion, index) in posicions.data" :key="index" class="text-sm text-gray-500">
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ posicion.name }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ posicion.cant_disponible }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <div class="mx-4 ">
                                    <InputError :message="posicion.error" />
                                    <InputGroup placeholder="" type="number" min="1" v-model="posicion.new_cantidad"
                                        @clickButton="updateSurtido(posicion)">
                                        <template #button>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                            </svg>
                                            <SpinProgress :inprogress="posicion.processing" />
                                        </template>
                                    </InputGroup>
                                </div>
                            </td>
                        </tr>
                    </template>

                </DataTable>
                <PaginationAxios :pagination="posicions" @loadPage="loadPage" />
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
