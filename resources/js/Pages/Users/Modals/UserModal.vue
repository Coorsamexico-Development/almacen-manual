import DialogModal from '@/Components/DialogModal.vue';
<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { computed } from "@vue/runtime-core";
import ComponentLabel from "../../../Components/ComponentLabel.vue";
import InputVue from "../../../Components/Input.vue";
import SelectComponent from "../../../Components/SelectComponent.vue";
import InputError from "../../../Jetstream/InputError.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/Button.vue';
import SpinProgress from "../../../Components/SpinProgress.vue";
import DialogModal from "../../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/DialogModal.vue";

const emit = defineEmits(["close", "messageError"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    typeForm: {
        type: String
    },
    user: {
        type: Object,
        require: true
    },
    roles: {
        type: Array,
        require: true
    }
});


const form = useForm({
    id: -1,
    email: '',
    name: '',
    nombre: '',
    ap_paterno: '',
    ap_materno: '',
    role_id: '',
    password: ''
});


const close = () => {
    emit('close');
}

const title = computed(() => {
    if (props.typeForm == 'create') {
        form.reset();
        return '<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 16 16">'
            + '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>'
            + '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>'
            + '</svg> Nuevo Usuario';
    } else {
        form.id = props.user.id
        form.email = props.user.email;
        form.name = props.user.name;
        form.nombre = props.user.nombre;
        form.ap_paterno = props.user.ap_paterno;
        form.ap_materno = props.user.ap_materno;
        form.role_id = props.user.role_id;
        form.password = '';

        return 'Actualizar Usuario';
    }
})


const createOrUpdate = async () => {
    if (document.getElementById("formUser").reportValidity()) {
        if (props.typeForm === 'create') {
            form.post(route('users.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    close()
                }
            });
        } else {
            if (form.password === '') {
                delete form.password;
            }
            form.put(route('users.update', props.user.id), {
                preserveScroll: true,
                onSuccess: () => {
                    close()
                },
                onError: (error) => {
                    console.log(error);
                }
            });
        }
    }
}

</script>

<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <h2 class="font-semibold text-md" v-html="title"></h2>
        </template>
        <template #content>
            <form id="formUser" @submit.prevent="createOrUpdate()">
                <div class="grid grid-cols-2 gap-2 md:grid-cols-2">
                    <div class="mt-2">
                        <ComponentLabel for="name">
                            Nombre de Usuario:<span class="text-red-400">*</span>
                        </ComponentLabel>

                        <InputVue id="name" type="text" placeholder="Nombre de Usuario" v-model="form.name"
                            class="block w-full mt-1" required maxlength="40" />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <ComponentLabel for="email">
                            Correo:<span class="text-red-400">*</span>
                        </ComponentLabel>
                        <InputVue id="email" type="email" placeholder="ejemplo@coorsamexico.com" v-model="form.email"
                            class="block w-full mt-1" required maxlength="100" />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <ComponentLabel for="nombre">
                            Nombre(s):<span class="text-red-400">*</span>
                        </ComponentLabel>
                        <InputVue id="nombre" type="text" placeholder="Nombre" v-model="form.nombre"
                            class="block w-full mt-1" required maxlength="30" />
                        <InputError :message="form.errors.nombre" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <ComponentLabel for="ap_paterno">
                            Apellido Paterno:<span class="text-red-400">*</span>
                        </ComponentLabel>
                        <InputVue id="ap_paterno" type="text" placeholder="Apellido Paterno" v-model="form.ap_paterno"
                            class="block w-full mt-1" required maxlength="30" />
                        <InputError :message="form.errors.ap_paterno" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <ComponentLabel for="ap_materno" value="Apellido Materno:" />
                        <InputVue id="ap_materno" type="text" placeholder="Apellido Materno (Opcional)"
                            v-model="form.ap_materno" class="block w-full mt-1" maxlength="30" />
                        <InputError :message="form.errors.ap_materno" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <ComponentLabel for="role_id">
                            Role:<span class="text-red-400">*</span>
                        </ComponentLabel>
                        <SelectComponent id="role_id" name="role_id" v-model="form.role_id"
                            class="block w-full mt-1 uppercase" required>
                            <option value="" disabled>Selecciona un role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </SelectComponent>
                        <InputError :message="form.errors.role_id" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <ComponentLabel for="password">
                            Contraseña:
                            <span class="text-red-400">*</span>
                        </ComponentLabel>
                        <InputVue id="password" type="password" placeholder="******" v-model="form.password"
                            class="block w-full mt-1" :required="typeForm === 'create'" maxlength="60" />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <div class="mx-2 my-2">
                <SecondaryButton @click="close()">
                    Cancel
                </SecondaryButton>
                <PrimaryButton class="ml-4" @click="createOrUpdate()" :disabled="form.processing">
                    <SpinProgress v-if="form.processing" :inprogress="form.processing" /> Guardar
                </PrimaryButton>
            </div>
        </template>
    </DialogModal>
</template>
