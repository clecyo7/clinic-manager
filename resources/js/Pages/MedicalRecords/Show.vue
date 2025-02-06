<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { DocumentTextIcon, ArrowLeftIcon, PencilIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    record: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('pt-BR');
};
</script>

<template>
    <Head title="Detalhes do Prontuário" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Prontuário {{ record?.patient?.name ? `- ${record.patient.name}` : '' }}
                </h2>
                <div class="flex items-center gap-2" v-if="record?.patient">
                    <Link
                        :href="route('medical-records.edit', record.id)"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-flex items-center gap-2"
                    >
                        <PencilIcon class="h-5 w-5" />
                        Editar
                    </Link>
                    <Link
                        :href="route('patients.show', record.patient.id)"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center gap-2"
                    >
                        <ArrowLeftIcon class="h-5 w-5" />
                        Voltar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12" v-if="record">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div v-if="record.appointment">
                                <h3 class="text-lg font-medium text-gray-900">Informações da Consulta</h3>
                                <div class="mt-2 text-sm text-gray-600">
                                    Data: {{ formatDate(record.appointment?.date_time) }}
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Diagnóstico</h3>
                                <div class="mt-2 text-sm text-gray-600 whitespace-pre-line">
                                    {{ record.diagnosis }}
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Plano de Tratamento</h3>
                                <div class="mt-2 text-sm text-gray-600 whitespace-pre-line">
                                    {{ record.treatment_plan }}
                                </div>
                            </div>

                            <div v-if="record.observations">
                                <h3 class="text-lg font-medium text-gray-900">Observações</h3>
                                <div class="mt-2 text-sm text-gray-600 whitespace-pre-line">
                                    {{ record.observations }}
                                </div>
                            </div>

                            <div v-if="record.attachments && record.attachments.length > 0">
                                <h3 class="text-lg font-medium text-gray-900">Anexos</h3>
                                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <a
                                        v-for="attachment in record.attachments"
                                        :key="attachment.id"
                                        :href="attachment.url"
                                        target="_blank"
                                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100"
                                    >
                                        <DocumentTextIcon class="h-6 w-6 text-gray-400 mr-2" />
                                        <span class="text-sm text-gray-900">{{ attachment.name }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        Carregando prontuário...
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>