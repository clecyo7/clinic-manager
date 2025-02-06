<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3'; // Add Link import
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    patients: Array
});

// Adiciona validação de data mínima
const minDateTime = new Date().toISOString().slice(0, 16);

const form = useForm({
    patient_id: '',
    date_time: '',
    notes: '',
    status: 'pendente' // Status padrão para novas consultas
});

const submit = () => {
    form.post(route('appointments.store'), {
        onSuccess: () => {
            console.log('Consulta criada com sucesso');
        }
    });
};
</script>

<template>
    <Head title="Nova Consulta" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Nova Consulta</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="patient_id" value="Paciente" />
                                <select
                                    id="patient_id"
                                    v-model="form.patient_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="">Selecione o paciente</option>
                                    <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                                        {{ patient.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.patient_id" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="date_time" value="Data e Hora" />
                                <TextInput
                                    id="date_time"
                                    v-model="form.date_time"
                                    type="datetime-local"
                                    :min="minDateTime"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.date_time" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="notes" value="Observações" />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    rows="3"
                                    placeholder="Digite suas observações aqui..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                                <InputError :message="form.errors.notes" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link
                                    :href="route('appointments.index')"
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    Cancelar
                                </Link>
                                <PrimaryButton :disabled="form.processing">
                                    {{ form.processing ? 'Criando...' : 'Criar Consulta' }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>