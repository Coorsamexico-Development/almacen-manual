<script setup>
import { watch, ref, reactive, watchEffect } from 'vue'
import { throttle } from 'lodash'

import { removeSelect, selectElement } from '../../../utils/main'

import DataTable from '../../../Components/DataTable.vue';
import SearchInput from '../../../Components/SearchInput.vue';
import PaginationAxios from '../../../Components/PaginationAxios.vue';
import InfoButton from '@/Components/InfoButton.vue';
import DangerButton from '@/Components/DangerButton.vue';



const emit = defineEmits(['selected', 'messageError']);



const folioSelect = ref({})



const props = defineProps({
    ordenEntrada: {
        type: Object,
        required: true
    },
});

const folios = ref({ data: [] });
const filters = reactive({ search: null, field: null, direction: null });




const getFoliosOrdenEntrada = async () => {
    try {
        const response = await axios.get(route('ordenes-entradas.folios.index', props.ordenEntrada.id));
        folios.value = response.data.folios;
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




const searchFolios = async () => {
    try {
        const response = await axios.get(route('ordenes-entradas.folios.index', props.ordenEntrada.id), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction
            }
        });
        folios.value = response.data.folios;
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
        const response = await axios.get(route('ordenes-entradas.folios.index', props.ordenEntrada.id), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction,
                page: page
            }
        });
        folios.value = response.data.folios;
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
let elementSelect;
const selectedRow = (event, folio) => {
    if (elementSelect) {
        removeSelect(elementSelect);
    }
    elementSelect = event.target.closest('tr');
    selectElement(elementSelect)
    emit('selected', folio);
}



// En Modal

watchEffect(() => {
    if (props.ordenEntrada.id !== -1) {
        getFoliosOrdenEntrada();
    }
})

watch(filters, throttle(function () {
    if (props.ordenEntrada.id !== -1)
        searchFolios();
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
                        <span class="" @click="sort('folios.name')">
                            FOLIO
                            <template v-if="filters.field === 'folios.name'">
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
                        <span class="" @click="sort('totals_productos')">
                            TOTAL DE PRODUCTOS
                            <template v-if="filters.field === 'totals_productos'">
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
                </tr>
            </template>
            <template #table-body>
                <tr v-for="(folio, index) in folios.data" :key="index" @click="selectedRow($event, folio)"
                    class="text-gray-500 cursor-pointer text-md selected-row">
                    <td class="px-2 text-sm whitespace-nowrap">

                        <span>
                            {{ folio.name }}
                        </span>
                    </td>
                    <td class="px-2 text-sm whitespace-nowrap">
                        {{ folio.totals_productos }}
                    </td>
                </tr>
            </template>
        </DataTable>
        <PaginationAxios :pagination="folios" @loadPage="loadPage" />
    </div>
</template>
