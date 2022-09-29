<script setup>
    import { reactive, ref, watch } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import { useForm } from '@inertiajs/inertia-vue3';
    import { pickBy, throttle } from 'lodash';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import DataTable from '@/Components/DataTable.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SpinProgress from '@/Components/SpinProgress.vue';
    import InfoButton from '../../Components/InfoButton.vue';
    import ActionMessage from '@/Components/ActionMessage.vue';
    import SearchInput from '@/Components/SearchInput.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import Pagination from '../../Components/Pagination.vue';
    import ProductosModal from './Partials/ProductosModal.vue';
    //comentario
    
    const props = defineProps
    ({
        productos:Object 
    });
    
    const params = reactive({
        search: "",
    })
    
    const form = useForm({
        file: [],
    });
    
    const file = ref(null);
    const fileName = ref(null);
    const ocSelected = ref({ id: -1 });
    const showingProductos = ref(false);
    const producto = ref({ id: -1 });

    const selectFile = () => {
        file.value.click();
    };
    
    const setFileName = () => 
    {
        const fileGet = file.value.files[0];
        fileName.value = fileGet.name;
    };

    const importProducts = () => {
    form.post(route('productos.import'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset(['file']);
        }
    })
};

  const abrirModalVerProductos = (productoSelect) => 
  {
    producto.value = productoSelect;
    showingProductos.value = true;
  }

  const closeModalProductos = () => 
  {
    showingProductos.value = false;
  } 

</script>
    
    <template>
        <AppLayout title="Entradas">
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Productos
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
                                <div class="flex items-center gap-2">
                                    <form id="formImport" class="flex" @submit.prevent="importProducts()">
                                        <div class="relative col-span-2">
                                            <InputLabel class="absolute -top-5" :value="fileName" />
                                            <input type="file" class="hidden" ref="file" name="file"
                                                @change="setFileName()"
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
                                    <a  class="block" download>
                                        <PrimaryButton type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2"
                                                fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                <path
                                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                            </svg> 
                                            Descargar Ejemplo
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
                            </template>
                            <template #table-header>
                                <tr class="text-center">
                                  <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span class="" @click="sort('name')">
                                        EAN
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
                                  <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                     <span>
                                        Descripcion
                                     </span>
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span class="" @click="sort('name')">
                                         Categoria
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
                                        CANTIDAD
                                    </span>
                                </th>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    <span>
                                        UBICACION
                                    </span>
                                 </th>
                              </tr>
                            </template>
                            <template #table-body>
                                <tr v-for="producto in productos" :key="producto.id">
                                    <td>{{producto.ean}}</td>
                                    <td>{{producto.name}}</td>
                                    <td>{{producto.familia_id}}</td>
                                    <td>
                                        <p v-if="producto.disponible > 0 && producto.disponible != null">
                                            {{producto.disponible}}
                                        </p>
                                    </td>
                                    <td>
                                        <PrimaryButton @click="abrirModalVerProductos(producto)">
                                            Ubicación
                                        </PrimaryButton>
                                    </td>
                                </tr>                               
                            </template>
                        </DataTable>
                        <Pagination :pagination="productos" />
                    </div>
                </div>
            </div>
            <!-- Modals -->
            <ProductosModal :show="showingProductos" :producto="producto" @close="closeModalProductos()" />
        </AppLayout>
    </template>
    