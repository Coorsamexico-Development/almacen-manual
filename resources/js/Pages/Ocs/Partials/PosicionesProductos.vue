<script setup>
import { reactive, ref, watch, watchEffect } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from '../../../Components/DataTable.vue';
import SearchInput from '../../../Components/SearchInput.vue';
import TextInput from '@/Components/TextInput.vue';
import PaginationAxios from '../../../Components/PaginationAxios.vue';
import { throttle } from 'lodash';

const emit = defineEmits(['close', 'messageError', 'showImagesTarima']);
const folioSelected = ref({ id: -1 })


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


const posiciones = ref({ data: [] });
const filters = reactive({ search: null, field: null, direction: null });




const getPosiciones = async () => {
    try {
        const response = await axios.get(route('posiciones.producto.index', props.oc.id));
        posiciones.value = response.data.posiciones;
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

const searchPosiciones = async () => {
    try {
        const response = await axios.get(route('posiciones.producto.index', props.oc.id), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction
            }
        });
        posiciones.value = response.data.posiciones;
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
        const response = await axios.get(route('posiciones.producto.index', props.oc.id), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction,
                page: page
            }
        });
        posiciones.value = response.data.posiciones;
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
const sort = (field) => {
    filters.field = field;
    filters.direction = filters.direction === "asc" ? "desc" : "asc";
}



// En Modal

watchEffect(() => {
    if (props.oc.id !== -1 && props.show) {
        getPosiciones();
    }
})

watch(filters, throttle(function () {
    if (props.oc.id !== -1)
        searchPosiciones();
}, 150))



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
                    posiciones SKUS
                </h1>
                <span class="p-1 font-semibold text-blue-900">
                    {{ oc.name }}
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
                                <span class="" @click="sort('productos.ean')">
                                    SKU
                                    <template v-if="filters.field === 'productos.ean'">
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
                                <span class="" @click="sort('productos.name')">
                                    salida
                                    <template v-if="filters.field === 'productos.name'">
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
                                <span class="" @click="sort('solicitado')">
                                    CANTIDAD SOLICITADA
                                    <template v-if="filters.field === 'solicitado'">
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
                                <span class="" @click="sort('surtido')">
                                    CANTIDAD SURTIDA
                                    <template v-if="filters.field === 'surtido'">
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
                                <span>
                                    SURTIRSE
                                </span>
                            </th>
                        </tr>
                    </template>
                    <template #table-body>
                        <tr v-for="(salida, index) in posiciones.data" :key="index" class="text-sm text-gray-500">
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ salida.ean }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ salida.producto }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <span class="px-2 py-1 text-white bg-yellow-600 rounded">
                                    {{ salida.solicitado }}
                                </span>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <span class="px-2 py-1 text-white bg-green-600 rounded">
                                    {{ salida.surtido }}
                                </span>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <SecondaryButton class="py-1">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4 mr-1"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                    </svg>
                                    SUBTIRSE
                                </SecondaryButton>
                            </td>
                        </tr>
                    </template>

                </DataTable>
                <PaginationAxios :pagination="posiciones" @loadPage="loadPage" />
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
