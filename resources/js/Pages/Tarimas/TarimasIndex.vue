<script setup>
import { reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { pickBy, throttle } from 'lodash';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '../../Components/DataTable.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from '../../Components/SpinProgress.vue';
import InfoButton from '../../Components/InfoButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SearchInput from '@/Components/SearchInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import EntradasModal from './Partials/EntradasModal.vue';
import Pagination from '../../Components/Pagination.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    filters: {
        type: Object,
        required: true
    },
    tarimas: {
        type: Object,
        required: true
    },
});

const params = reactive({
    search: props.filters.search,
    field: props.filters.field,
    direction: props.filters.direction,
})



const tarima = ref({ id: -1 });
const showingEntradas = ref(false);


const form = useForm();

const sort = (field) => {
    params.field = field;
    params.direction = params.direction === "asc" ? "desc" : "asc";
};


const showTarimastarimas = (tarimaselect) => {
    tarima.value = tarimaselect;
    showingEntradas.value = true;
}

const createTarima = () => {
    form.post(route('tarimas.store'), {
        preserveScroll: true,
        preserveState: true,
        only: ['tarimas'],
    })
}


watch(params, throttle(function () {
    let paramsClear = pickBy(params);
    Inertia.get(route("tarimas.index"), paramsClear, {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
}, 150));

</script>

<template>
    <AppLayout title="Entarimado">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Entarimado
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <DataTable>
                        <template #section-header>
                            <div>
                                <SearchInput v-model="params.search" />
                            </div>
                            <div class="flex gap-2">
                                <SecondaryButton @click="createTarima()">
                                    <span class="mr-2">Nueva Tarima</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                    </svg>
                                </SecondaryButton>
                                <ActionMessage :on="form.recentlySuccessful" class="ml-2 text-green-700">
                                    Guardado..
                                </ActionMessage>
                                <ActionMessage :on="form.hasErrors" class="ml-2 text-red-500">
                                    <ul>
                                        <li v-for="(error, index) in form.errors" :key="index">
                                            {{error}}
                                        </li>
                                    </ul>
                                </ActionMessage>
                            </div>

                        </template>
                        <template #table-header>
                            <tr class="text-center">
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span class="" @click="sort('tarimas.id')">
                                        TARIMA
                                        <template v-if="params.field === 'tarimas.id'">
                                            <svg v-if="params.direction === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                                            </svg>
                                            <svg v-if="params.direction === 'desc'" xmlns="http://www.w3.org/2000/svg"
                                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </template>
                                    </span>
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    PRODUCTOS
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    CANTIDAD DE CAJAS
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span>
                                        POSICION
                                    </span>
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span>
                                        FECHA DE CREACION
                                    </span>
                                </th>
                            </tr>
                        </template>
                        <template #table-body>
                            <tr v-for="(tarima, index) in tarimas.data" :key="index" class="text-sm text-gray-500">

                                <td class="px-2 py-1 whitespace-nowrap">
                                    {{ tarima.name }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    <SecondaryButton @click="showTarimastarimas(tarima)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z" />
                                        </svg>
                                    </SecondaryButton>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    <span class="px-2 text-white bg-yellow-300 rounded">

                                        {{ tarima.canitdad_cajas }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    <SecondaryButton @click="showTarimastarimas(tarima)">
                                        <span class="mr-2" v-if="tarima.posicion !== null">{{tarima.posicion}}</span>
                                        <span class="mr-2" v-else>SIN POSICION</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8l3-4z" />
                                            <path fill-rule="evenodd"
                                                d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z" />
                                        </svg>
                                    </SecondaryButton>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    {{ tarima.created_at }}
                                </td>
                            </tr>
                        </template>

                    </DataTable>
                    <Pagination :pagination="tarimas" />
                </div>
            </div>
        </div>
        <EntradasModal :show="showingEntradas" :tarima="tarima" @close="showingEntradas = false" />
    </AppLayout>
</template>
