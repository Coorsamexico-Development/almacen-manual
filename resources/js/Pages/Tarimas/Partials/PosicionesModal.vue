<script setup>
import { reactive, ref, watch, watchEffect } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '../../../Components/SearchInput.vue';
import { throttle } from 'lodash';
import DataTable from '../../../Components/DataTable.vue';
import PaginationAxios from '../../../Components/PaginationAxios.vue';
import ActionMessage from '@/Components/ActionMessage.vue';


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

const posicions = ref({ data: [] });
const messageUpdate = ref('');
const filters = reactive({ search: null, field: null, direction: null });

const getPosiciones = async () => {
    try {
        const response = await axios.get(route('posiciones-disponible.index'));
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




const searchPosiciones = async () => {
    try {
        const response = await axios.get(route('posiciones-disponible.index'), {
            params: {
                search: filters.search,
                field: filters.field,
                direction: filters.direction
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

const loadPage = async (page) => {
    try {
        const response = await axios.get(route('posiciones-disponible.index'), {
            params: {
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
const sort = (field) => {
    filters.field = field;
    filters.direction = filters.direction === "asc" ? "desc" : "asc";
}

const changePosition = async (posicion) => {
    try {
        const response = await axios.put(route('tarimas.posiciones.update', props.tarima.id), {
            posicion_id: posicion.id,

        });
        messageUpdate.value = response.data.message;
        props.tarima.posicion = posicion.name;
        searchPosiciones();
        setTimeout(() => {
            messageUpdate.value = '';
        }, 400)
    } catch (error) {
        console.log(error);
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


const close = () => {
    emit('close');
};


watchEffect(() => {
    if (props.tarima.id !== -1 && props.show) {
        getPosiciones();
    }
})

watch(filters, throttle(function () {
    if (props.tarima.id !== -1)
        searchPosiciones();
}, 150))



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
                <span class="px-2 text-white bg-yellow-700 rounded">
                    {{tarima.posicion}}
                </span>
                <ActionMessage :on="messageUpdate !==''" class="ml-2 text-green-500">
                    {{messageUpdate}}
                </ActionMessage>
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
                                <span class="" @click="sort('rack')">
                                    RACK
                                    <template v-if="filters.field === 'rack'">
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
                                <span class="" @click="sort('status')">
                                    STATUS
                                    <template v-if="filters.field === 'status'">
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
                                <span class="" @click="sort('nivel')">
                                    NIVEL
                                    <template v-if="filters.field === 'nivel'">
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
                                <span class="" @click="sort('columna')">
                                    COLUMNA
                                    <template v-if="filters.field === 'columna'">
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
                        <tr v-for="(posicion, index) in posicions.data" :key="index"
                            class="text-gray-500 cursor-pointer text-md hover:bg-gray-800 hover:text-white">

                            <td class="px-2 py-1 whitespace-nowrap">

                                <span class="px-2 py-1 mr-2 text-white bg-yellow-500 rounded">
                                    {{ posicion.name }}
                                </span>
                                <SecondaryButton @click="changePosition(posicion)">
                                    MOVER
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z" />
                                        <path fill-rule="evenodd"
                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z" />
                                    </svg>
                                </SecondaryButton>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ posicion.rack }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ posicion.status }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ posicion.nivel }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ posicion.columna }}
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
