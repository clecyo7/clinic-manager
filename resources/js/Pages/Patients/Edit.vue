<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    patient: Object
});

const formatDateForInput = (date) => {
    return new Date(date).toISOString().split('T')[0];
};

const form = useForm({
    name: props.patient.name,
    email: props.patient.email || '',
    phone: props.patient.phone,
    birth_date: formatDateForInput(props.patient.birth_date),
    gender: props.patient.gender,
    address: props.patient.address || '',
});

const submit = () => {
    form.put(route('patients.update', props.patient.id));
};
</script>

<template>
    <Head title="Editar Paciente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Paciente</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Nome" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="birth_date" value="Data de Nascimento" />
                                <TextInput
                                    id="birth_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.birth_date"
                                    required
                                />
                                <InputError :message="form.errors.birth_date" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="gender" value="Gênero" />
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Selecione o gênero</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                    <option value="outro">Outro</option>
                                </select>
                                <InputError :message="form.errors.gender" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="phone" value="Telefone" />
                                <TextInput
                                    id="phone"
                                    type="tel"
                                    class="mt-1 block w-full"
                                    v-model="form.phone"
                                    required
                                />
                                <InputError :message="form.errors.phone" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link
                                    :href="route('patients.index')"
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    Cancelar
                                </Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Atualizar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>