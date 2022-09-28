<script setup>
import { reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
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
import Pagination from '../../Components/Pagination.vue';
import ProductosModal from './Partials/ProductosModal.vue';


const props = defineProps({
    filters: {
        type: Object,
        required: true
    },
    ocs: {
        type: Object,
        required: true
    },
});

const params = reactive({
    search: props.filters.search,
    field: props.filters.field,
    direction: props.filters.direction,
})

const form = useForm({
    file: [],
});

const file = ref(null);
const fileName = ref(null);
const ocSelected = ref({ id: -1 });
const showingProductosModal = ref(false);

const selectFile = () => {
    file.value.click();
};
const setFileName = () => {
    const fileGet = file.files[0];
    fileName.value = fileGet.name;
};

const importOcs = () => {
    form.post(route('ocs.import'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset(['file']);
        }
    })
};
const sort = (field) => {
    params.field = field;
    params.direction = params.direction === "asc" ? "desc" : "asc";
};


const showProductos = (entradaSelect) => {
    ocSelected.value = entradaSelect;
    showingProductosModal.value = true;
}
const closeModalProductos = () => {
    ocSelected.value = { id: -1 };
    showingProductosModal.value = false;
}


watch(params, throttle(function () {
    let paramsClear = pickBy(params);
    Inertia.get(route("ocs.index"), paramsClear, {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
}, 150));

</script>

<template>
    <AppLayout title="Entradas">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Orden de Compra
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
                            <div>
                                <div class="flex items-center gap-2">
                                    <form id="formImport" class="flex" @submit.prevent="importOcs()">
                                        <div class="relative col-span-2">
                                            <InputLabel class="absolute -top-5" :value="fileName" />
                                            <input type="file" class="hidden" ref="file" name="file"
                                                @input="form.file = $event.target.files[0]" @change="setFileName()"
                                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <PrimaryButton class="mr-2" type="button" @click="selectFile()">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2"
                                                    viewBox="0 0 32.294 31.373">
                                                    <g id="Grupo_121" data-name="Grupo 121"
                                                        transform="translate(-151.33 -440.732)">
                                                        <path id="Trazado_150" data-name="Trazado 150"
                                                            d="M2821.419,472.105h-18.335a4.848,4.848,0,0,1-4.842-4.843V448.927a4.848,4.848,0,0,1,4.842-4.843h18.335a4.848,4.848,0,0,1,4.843,4.843v18.335A4.849,4.849,0,0,1,2821.419,472.105Zm-18.335-26.668a3.494,3.494,0,0,0-3.49,3.49v18.335a3.494,3.494,0,0,0,3.49,3.49h18.335a3.494,3.494,0,0,0,3.49-3.49V448.927a3.493,3.493,0,0,0-3.49-3.49Z"
                                                            transform="translate(-2642.638)" fill="#fff" />
                                                        <rect id="Rectángulo_183" data-name="Rectángulo 183"
                                                            width="26.668" height="26.668" rx="6.258"
                                                            transform="translate(151.33 440.732)" fill="#fff" />
                                                    </g>
                                                </svg>
                                                SELECIONA UN ARCHIVO
                                            </PrimaryButton>
                                        </div>
                                        <InfoButton type="submit" class="mr-3" :disabled="form.processing">
                                            <SpinProgress v-if="form.processing" :inprogress="form.processing" /> SUBIR
                                        </InfoButton>
                                    </form>
                                    <a :href="route('ocs.export.example')" class="block" download>
                                        <PrimaryButton type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2"
                                                fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                <path
                                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                            </svg> Descargar Ejemplo
                                        </PrimaryButton>
                                    </a>
                                </div>
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
                                    <span class="" @click="sort('name')">
                                        O.C
                                        <template v-if="params.field === 'name'">
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
                                    <span>
                                        SKUS
                                    </span>
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span class="" @click="sort('name')">
                                        FECHA DE ENTREGA
                                        <template v-if="params.field === 'name'">
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
                                    <span>
                                        CLIENTE
                                    </span>
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span>
                                        STATUS
                                    </span>
                                </th>
                            </tr>
                        </template>
                        <template #table-body>
                            <tr v-for="(oc, index) in ocs.data" :key="index" class="text-sm text-gray-500">
                                <td class="px-2 py-1 whitespace-nowrap">
                                    {{ oc.name }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    <SecondaryButton @click="showProductos(oc)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z" />
                                        </svg>
                                    </SecondaryButton>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    {{oc.fecha_entrega}}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    {{oc.cliente}}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    <div class="flex justify-center">

                                        <svg v-if="oc.active" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="w-4 h-4" viewBox="0 0 16 16">
                                            <path
                                                d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
                                            <path
                                                d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="w-4 h-4" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z" />
                                            <path
                                                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z" />
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </DataTable>
                    <Pagination :pagination="ocs" />
                </div>
            </div>
        </div>
        <!-- Modals -->
        <ProductosModal :show="showingProductosModal" :oc="ocSelected" @close="closeModalProductos()" />
    </AppLayout>
</template>
