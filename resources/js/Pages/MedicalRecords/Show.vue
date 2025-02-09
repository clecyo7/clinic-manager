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
    return new Date(date).toLocaleDateString('pt-BR');
};
</script>

<template>
    <Head title="Visualizar Prontuário" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Prontuário {{ record.patient?.name ? `- ${record.patient.name}` : '' }}
                </h2>
                <Link :href="route('medical-records.edit', record.id)"
                      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar Prontuário
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Data da Consulta</h3>
                        <p>{{ formatDate(record.appointment.date_time) }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Histórico Clínico</h3>
                        <p class="whitespace-pre-line">{{ record.diagnosis }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Evolução</h3>
                        <p class="whitespace-pre-line">{{ record.treatment_plan }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Observações</h3>
                        <p class="whitespace-pre-line">{{ record.observations || 'Nenhuma observação registrada' }}</p>
                    </div>

                    <div v-if="record.attachments && record.attachments.length > 0">
                        <h3 class="text-lg font-semibold mb-2">Anexos</h3>
                        <ul class="list-disc pl-5">
                            <li v-for="attachment in record.attachments" :key="attachment.id">
                                <a :href="attachment.url" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ attachment.name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>