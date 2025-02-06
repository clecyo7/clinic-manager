<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { PencilIcon, DocumentTextIcon } from '@heroicons/vue/24/outline';

defineProps({
    patient: Object
});

const formatDate = (date) => {
    if (!date) return '';
    const dateObj = new Date(date);
    return dateObj.toLocaleDateString('pt-BR', {
        timeZone: 'UTC'
    });
};

const getGenderLabel = (gender) => {
    const labels = {
        'M': 'Masculino',
        'F': 'Feminino',
        'O': 'Outro'
    };
    return labels[gender] || gender;
};
</script>

<template>
    <Head title="Detalhes do Paciente" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalhes do Paciente</h2>
                <div class="flex gap-2">
                    <Link
                        :href="route('patients.medical-records.create', patient.id)"
                        class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-500 inline-flex items-center gap-2"
                    >
                        <DocumentTextIcon class="h-5 w-5" />
                        Novo Prontuário
                    </Link>
                    <Link
                        :href="route('patients.edit', patient.id)"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 inline-flex items-center gap-2"
                    >
                        <PencilIcon class="h-5 w-5" />
                        Editar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ patient.name }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Data de Nascimento</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(patient.birth_date) }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Gênero</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ getGenderLabel(patient.gender) }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ patient.phone }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>