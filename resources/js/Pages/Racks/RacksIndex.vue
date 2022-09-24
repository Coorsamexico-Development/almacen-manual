<script setup>
import { ref, watchEffect } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import ListSecciones from '../../Components/ListSecciones.vue';
import SelectComponent from '../../Components/SelectComponent.vue';
import { useForm } from '@inertiajs/inertia-vue3';



const RowOrColumn = ref("Fila");

let selectedRack = ref({ id: -1 });

const listaFilasOrColumn = ref([]);

const formRack = useForm({
    name: ''
});




const changeRack = (rack) => {
    selectedRack.value = rack;
}

const createRack = (form) => {
    form.processing = true;
    formRack.post(route('racks.store'), {
        preserveScroll: true,
        preserveState: true,
        only: ['racks',],
        onSuccess: () => {
            formRack.reset();
            form.processing = false;
            form.hasError = false
        },
        onError: () => {
            form.processing = false;
            form.hasError = true;
            form.error = formRack.error;
        }
    })
}

const getFilasRack = async () => {
    await axios.get(route('racks.filas.index', selectedRack.value.id))
        .then((resp) => {
            listaFilasOrColumn.value = resp.data
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
    await axios.get(route('racks.columnas.index', selectedRack.value.id))
        .then((resp) => {
            listaFilasOrColumn.value = resp.data
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
        if (RowOrColumn.value === 'Fila') {
            getFilasRack()
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-2 gap-1">
                        <ListSecciones :secciones="[{id:1,name: 'Racks'}, {id:2,name: 'Racks 2'}]"
                            @create-seccion="createRack($event)" @getSeccion="changeRack($event)">
                            <h2 class="pb-2 text-lg text-center text-gray-700">
                                Racks
                            </h2>
                        </ListSecciones>
                        <ListSecciones v-if="selectedRack.id !==-1" title="Filas y Columnas"
                            :secciones="listaFilasOrColumn">
                            <SelectComponent class="w-full" v-model="RowOrColumn">
                                <option value="Fila">
                                    Filas
                                </option>
                                <option value="Columna">
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
