<script setup>
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from '@/Components/DataTable.vue';
const emit = defineEmits(['close', 'messageError', 'showImagesTarima']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    producto: { 
        type:Object
    },
});


const close = () => {
    emit('close');
};


</script>


<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <div class="flex gap-2 px-2">
                <h1 class="font-semibold text-gray-600 text-md">
                    Ubicacion {{props.producto[0].name}}
                </h1>
            </div>
        </template>
        <template #content>
            <div class="w-full">
                <DataTable>
                    <template #table-header>
                             <tr class="text-center" >
                             <th style="padding-left:2rem">EAN</th>
                             <th style="padding-left:2rem">Tarima</th>
                             <th style="padding-left:2rem">Posicion</th>
                             <th style="padding-left:2rem; padding-right: 2rem;">Rack</th>
                             <th>Disponible</th>   
                        </tr>
                    </template>
                    <template #table-body>
                        <tr v-for="dato in producto" :key="dato.id">
                            <td>{{dato.ean}}</td>    
                            <td>{{dato.tarima_nombre}}</td>    
                            <td>{{dato.posicion_name}}</td>     
                            <td>{{dato.rack_name}}</td>  
                            <td>{{dato.disponible}}</td>         
                        </tr>                               
                    </template>
                 </DataTable>
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
