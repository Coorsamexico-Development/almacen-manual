import DialogModal from '@/Components/DialogModal.vue';
<script setup>
import { reactive, ref, watch, watchEffect } from "@vue/runtime-core";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from "../../../Components/SpinProgress.vue";
import DialogModal from "@/Components/DialogModal.vue";
import DataTable from "../../../Components/DataTable.vue";
import SearchInput from "../../../Components/SearchInput.vue";
import InputError from "../../../Components/InputError.vue";
import TextInput from "../../../Components/TextInput.vue";
import PaginationAxios from "../../../Components/PaginationAxios.vue";
import { throttle } from 'lodash'
import DangerButton from "../../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Components/DangerButton.vue";

const emit = defineEmits(["close", "messageError"]);
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

const entradas = ref({ data: [] });
const filters = reactive({ search: null, field: null, direction: null });




const getEntradas = async () => {
    try {
        const response = await axios.get(route('entradas.index', props.tarima.id));
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
        const response = await axios.get(route('entradas.index', props.tarima.id), {
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
        const response = await axios.get(route('entradas.index', props.tarima.id), {
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
const sort = (field) => {
    filters.field = field;
    filters.direction = filters.direction === "asc" ? "desc" : "asc";
}


const storeCanitdadTarima = async (entrada) => {
    try {
        const response = await axios.post(route('tarimas.entradas-productos.store', props.tarima.id), {
            entrada_id: entrada.id,
            cantidad: entrada.new_cantidad,

        });
        entrada.cantidad_disponible = parseInt(entrada.cantidad_disponible) - parseInt(entrada.new_cantidad)
        entrada.cantidad_tarima = parseInt(entrada.cantidad_tarima) + parseInt(entrada.new_cantidad);
        props.tarima.canitdad_cajas = parseInt(props.tarima.canitdad_cajas) + parseInt(entrada.new_cantidad)
        entrada.new_cantidad = '';
        entrada.error = '';
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


const destroyCanitdadTarima = async (entrada) => {
    try {
        const response = await axios.delete(route('tarimas.entradas-productos.destroy', props.tarima.id), {
            params: {
                entrada_id: entrada.id,
                cantidad: entrada.new_cantidad,
            }
        });
        entrada.cantidad_disponible = parseInt(entrada.cantidad_disponible) + parseInt(entrada.new_cantidad)
        entrada.cantidad_tarima = parseInt(entrada.cantidad_tarima) - parseInt(entrada.new_cantidad);
        props.tarima.canitdad_cajas = parseInt(props.tarima.canitdad_cajas) - parseInt(entrada.new_cantidad)
        entrada.new_cantidad = '';
        entrada.error = '';
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



// En Modal

watchEffect(() => {
    if (props.tarima.id !== -1 && props.show) {
        getEntradas();
    }
})

watch(filters, throttle(function () {
    if (props.tarima.id !== -1)
        searchEntradas();
}, 150))



const close = () => {
    emit('close');
}



</script>

<template>
    <DialogModal :show="show" maxWidth="4xl" @close="close()">
        <template #title>
            <h2 class="font-semibold text-md">
                Lista de Productos Ingresados
                <span class="px-2 text-white bg-gray-700 rounded">
                    {{tarima.name}}
                </span>
            </h2>
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
                                <span class="" @click="sort('folio')">
                                    FOLIO
                                    <template v-if="filters.field === 'folio'">
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
                            <th scope="col"
                                class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                <span class="" @click="sort('producto')">
                                    PRODUCTO
                                    <template v-if="filters.field === 'producto'">
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
                                <span class="" @click="sort('canitdad_disponible')">
                                    CANTIDAD DISPONIBLE
                                    <template v-if="filters.field === 'canitdad_disponible'">
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
                                <span class="" @click="sort('canitdad_disponible')">
                                    CANTIDAD TARIMA
                                    <template v-if="filters.field === 'canitdad_disponible'">
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
                                    AÑADIR
                                </span>
                            </th>
                        </tr>
                    </template>
                    <template #table-body>
                        <tr v-for="(entrada, index) in entradas.data" :key="index" class="text-sm text-gray-500">

                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ entrada.folio }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ entrada.ean }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ entrada.producto }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <span class="px-2 py-1 text-white bg-yellow-600 rounded">

                                    {{ entrada.cantidad_disponible }}
                                </span>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <span class="px-2 py-1 text-white bg-green-600 rounded">

                                    {{ entrada.cantidad_tarima }}
                                </span>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <div class="flex">

                                    <div class="w-1/2">

                                        <TextInput class="w-full" type="number" v-model="entrada.new_cantidad"
                                            min="1" />
                                        <InputError :message="entrada.error" />
                                    </div>
                                    <div class="w-1/2">

                                        <SecondaryButton class="py-1" @click="storeCanitdadTarima(entrada)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                            </svg>
                                        </SecondaryButton>
                                        <br />
                                        <DangerButton class="py-1" @click="destroyCanitdadTarima(entrada)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
                                            </svg>
                                        </DangerButton>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>

                </DataTable>
                <PaginationAxios :pagination="entradas" @loadPage="loadPage" />
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
