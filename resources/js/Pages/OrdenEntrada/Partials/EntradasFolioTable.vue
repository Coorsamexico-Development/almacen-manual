<script setup>
import { watch, ref, reactive, watchEffect } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3';
import { throttle } from 'lodash'


import DataTable from '../../../Components/DataTable.vue';
import SearchInput from '../../../Components/SearchInput.vue';
import PaginationAxios from '../../../Components/PaginationAxios.vue';
import InfoButton from '@/Components/InfoButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';



const emit = defineEmits(['selected', 'messageError']);


const props = defineProps({
    folio: {
        type: Object,
        required: true
    },
});

const entradas = ref({ data: [] });
const filters = reactive({ search: null, field: null, direction: null });




const getEntradasFolio = async () => {
    try {
        const response = await axios.get(route('folios.entradas.index', props.folio.id));
        entradas.value = response.data.entradas;
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


const searchEntradas = async () => {
    try {
        const response = await axios.get(route('folios.entradas.index', props.folio.id), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction
            }
        });
        entradas.value = response.data.entradas;
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
        const response = await axios.get(route('folios.entradas.index', props.folio.id), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction,
                page: page
            }
        });
        entradas.value = response.data.entradas;
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


const storeCantidadReal = async (entrada) => {
    try {
        const response = await axios.post(route('entradas.entradas-reales.store', entrada.id), {
            cantidad: entrada.new_cantidad
        });
        entrada.cantidad_real = parseInt(entrada.cantidad_real) + parseInt(entrada.new_cantidad);
        entrada.new_cantidad = '';

        entrada.error = '';
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
            entrada.error = messageError;
        }
    }
}
const sort = (field) => {
    filters.field = field;
    filters.direction = filters.direction === "asc" ? "desc" : "asc";
}



// En Modal

watchEffect(() => {
    if (props.folio.id !== -1) {
        getEntradasFolio();
    }
})

watch(filters, throttle(function () {
    if (props.folio.id !== -1)
        searchEntradas();
}, 150))

</script>

<template>
    <div class="sm:rounded-lg">
        <DataTable maxheight="65vh">
            <template #section-header>
                <SearchInput v-model="filters.search" />
                <slot name="section-header">
                </slot>
            </template>
            <template #table-header>
                <tr class="text-center text-md">
                    <th scope="col" class="px-2 py-1 text-sm font-semibold tracking-wider uppercase cursor-pointer ">
                        <span class="" @click="sort('ean')">
                            EAN
                            <template v-if="filters.field === 'ean'">
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
                    <th scope="col" class="px-2 py-1 text-sm font-semibold tracking-wider uppercase cursor-pointer ">
                        <span class="" @click="sort('name')">
                            PRODUCTO
                            <template v-if="filters.field === 'name'">
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
                    <th scope="col" class="px-2 py-1 text-sm font-semibold tracking-wider uppercase cursor-pointer ">
                        <span class="" @click="sort('cantidad')">
                            CANTIDAD
                            <template v-if="filters.field === 'cantidad'">
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
                    <th scope="col" class="px-2 py-1 text-sm font-semibold tracking-wider uppercase cursor-pointer ">
                        <span class="">
                            AVANCE
                        </span>
                    </th>
                    <th scope="col" class="px-2 py-1 text-sm font-semibold tracking-wider uppercase cursor-pointer ">
                        <span class="">
                            AÑADIR
                        </span>
                    </th>
                </tr>
            </template>
            <template #table-body>
                <tr v-for="(entrada, index) in entradas.data" :key="index"
                    class="text-gray-500 cursor-pointer text-md selected-row">
                    <td class="px-2 text-sm whitespace-nowrap">
                        {{ entrada.ean }}
                    </td>
                    <td class="px-2 text-sm whitespace-nowrap">
                        {{ entrada.producto }}
                    </td>
                    <td class="px-2 text-sm whitespace-nowrap">
                        {{ entrada.cantidad }}
                    </td>
                    <td class="px-2 text-sm whitespace-nowrap">
                        {{ entrada.cantidad_real }}
                    </td>
                    <td class="px-2 text-sm whitespace-nowrap">
                        <TextInput type="number" v-model="entrada.new_cantidad" min="1" />
                        <InputError :message="entrada.error" />
                        <SecondaryButton class="p-0" @click="storeCantidadReal(entrada)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                            </svg>
                        </SecondaryButton>
                    </td>
                </tr>
            </template>
        </DataTable>
        <PaginationAxios :pagination="entradas" @loadPage="loadPage" />
    </div>
</template>
