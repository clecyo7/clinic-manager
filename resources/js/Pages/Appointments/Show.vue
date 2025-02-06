<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { PencilIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    appointment: Object
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('pt-BR');
};

const getStatusColor = (status) => {
    const colors = {
        pendente: 'bg-yellow-100 text-yellow-800',
        concluida: 'bg-green-100 text-green-800',
        cancelada: 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Detalhes da Consulta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detalhes da Consulta
                </h2>
                <div class="flex gap-2">
                    <Link
                        v-if="!appointment.payment"
                        :href="route('appointments.payments.create', appointment.id)"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        <CurrencyDollarIcon class="h-5 w-5 mr-2" />
                        Registrar Pagamento
                    </Link>
                    <Link
                        :href="route('appointments.edit', appointment.id)"
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
                                <dt class="text-sm font-medium text-gray-500">Paciente</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ appointment.patient.name }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Data e Hora</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(appointment.date_time) }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm">
                                    <span :class="`px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(appointment.status)}`">
                                        {{ appointment.status }}
                                    </span>
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Observações</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ appointment.notes || 'Sem observações' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex justify-end">
                            <Link
                                :href="route('appointments.index')"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Voltar para lista
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>