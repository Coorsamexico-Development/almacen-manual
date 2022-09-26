<script setup>
import { reactive, watch } from 'vue';
import { throttle, pickBy } from 'lodash';

import DataTable from '../../../Components/DataTable.vue';
import PrimaryButton from '@/Components/Button.vue';
import InputSearch from '@/Components/InputSearch.vue';
import SwitchButton from '@/Components/SwitchButton.vue';
import InfoButton from '@/Components/InfoButton.vue';
import { Inertia } from '@inertiajs/inertia';



defineEmits(['create', 'delete', 'edit']);


const props = defineProps({
    filters: {
        type: Object,
        required: true
    },
    users: {
        type: Object,
        required: true
    },
});

const params = reactive({
    search: props.filters.search,
    field: props.filters.field,
    direction: props.filters.direction,
})

const sort = (field) => {

    params.field = field;
    params.direction = params.direction === "asc" ? "desc" : "asc";
};

watch(params, throttle(function () {
    let paramsClear = pickBy(params);
    Inertia.get(route("users.index"), paramsClear, {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
}, 150));




</script>
<template>
    <DataTable>
        <template #section-header>
            <PrimaryButton class="ml-4" @click="$emit('create')">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>Nuevo
            </PrimaryButton>
            <InputSearch v-model="params.search" />
        </template>
        <template #table-header>
            <tr class="text-center">
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="" @click="sort('users.nombre')">
                        DATOS PERSONALES
                        <template v-if="params.field === 'users.nombre'">
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
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="" @click="sort('users.name')">
                        USERNAME
                        <template v-if="params.field === 'users.name'">
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
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="" @click="sort('roles.name')">
                        ROLE
                        <template v-if="params.field === 'roles.name'">
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
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="" @click="sort('acive')">
                        STATUS
                        <template v-if="params.field === 'active'">
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
            </tr>
        </template>
        <template #table-body>
            <tr v-for="(user, index) in users" :key="index" class="text-sm text-gray-500">
                <td class="px-2 py-1 whitespace-nowrap">
                    <div class="flex items-center gap-2 mx-2">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ user.nombre }} {{ user.ap_paterno }} {{ user.ap_materno }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ user.email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-2 py-1 whitespace-nowrap">
                    {{ user.name }}
                </td>
                <td class="px-2 py-1 whitespace-nowrap">
                    {{ user.role }}
                </td>
                <td class="px-2 py-1 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <SwitchButton :name="'active-' + index" v-model:checked="user.active"
                            @change="$emit('delete', user)" />
                        <InfoButton class="px-2 py-2 w-9 h-7" @click="$emit('edit', user)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="w-16 h-5 mx-auto"
                                viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </InfoButton>
                    </div>
                </td>
            </tr>
        </template>

    </DataTable>
</template>
