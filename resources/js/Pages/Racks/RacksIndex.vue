<script setup>
import { ref, watchEffect } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ListSecciones from '../../Components/ListSecciones.vue';
import SelectComponent from '../../Components/SelectComponent.vue';
import { useForm } from '@inertiajs/inertia-vue3';

defineProps({
    racks: {
        type: Object,
        required: true,
    }
});

const RowOrColumn = ref("nivel");

let selectedRack = ref({ id: -1 });

const listaNivelesOrColumn = ref([]);

const formRack = useForm({
    cantidad: ''
});




const changeRack = (rack) => {
    selectedRack.value = rack;
}

const createRack = (form) => {
    form.processing = true;
    formRack.cantidad = form.cantidad;
    formRack.post(route('racks.store'), {
        preserveScroll: true,
        preserveState: true,
        only: [],
        onSuccess: () => {

            formRack.reset();
            form.processing = false;
            form.hasError = false
        },
        onError: (e) => {
            console.log("Error", e);
            form.processing = false;
            form.hasErrors = true;
            form.error = e.cantidad;
        }
    })
}

const createNivelOrColumn = async (form) => {
    const ruta = RowOrColumn.value === 'nivel' ? route('racks.nivels.store', selectedRack.value.id) : route('racks.columns.store', selectedRack.value.id)
    await axios.post(ruta, form)
        .then((resp) => {
            listaNivelesOrColumn.value = listaNivelesOrColumn.value.concat(resp.data)
        })
        .catch((error) => {
            if (error.response.data.hasOwnProperty('errors')) {

                alert(error.response.data.message);
            } else {
                alert("Error GET" + selectedRack.value);
            };
        });

}
const getNivelesRack = async () => {
    await axios.get(route('racks.nivels.index', selectedRack.value.id))
        .then((resp) => {
            listaNivelesOrColumn.value = resp.data
        })
        .catch((error) => {
            if (error.response.data.hasOwnProperty('errors')) {

                alert(error.response.data.message);
            } else {
                alert("Error GET" + selectedRack.value);
            };
        });

}
const getColumnsRack = async () => {
    await axios.get(route('racks.columns.index', selectedRack.value.id))
        .then((resp) => {
            listaNivelesOrColumn.value = resp.data
        })
        .catch((error) => {
            if (error.response.data.hasOwnProperty('errors')) {

                alert(error.response.data.message);
            } else {
                alert("Error GET" + selectedRack.value);
            };
        });
}

watchEffect(() => {
    if (selectedRack.value.id !== -1) {
        if (RowOrColumn.value === 'nivel') {
            getNivelesRack()
        } else {
            getColumnsRack()
        }
    }

});

</script>

<template>
    <AppLayout title="Racks">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Racks
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-2 gap-1">
                        <ListSecciones :secciones="racks" typeInput="number" @create-seccion="createRack($event)"
                            @getSeccion="changeRack($event)">
                            <h2 class="pb-2 text-lg text-center text-gray-700">
                                Racks
                            </h2>
                        </ListSecciones>
                        <ListSecciones v-if="selectedRack.id !==-1" title="Niveles y Columnas" typeInput="number"
                            :secciones="listaNivelesOrColumn" @create-seccion="createNivelOrColumn($event)">
                            <SelectComponent class="w-full" v-model="RowOrColumn">
                                <option value="nivel">
                                    Niveles
                                </option>
                                <option value="columna">
                                    Columnas
                                </option>
                            </SelectComponent>
                        </ListSecciones>
                        <div v-else class="flex items-center justify-center m-2 border-2 border-solid">
                            <h3 class="p-4 text-xl text-gray-500 ">Seleciona una Rack Para visualizar las
                                {{RowOrColumn}}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
