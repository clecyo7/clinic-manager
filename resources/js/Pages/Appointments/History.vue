<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    ClockIcon,
    UserIcon,
    DocumentTextIcon,
    ArrowLeftIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    appointment: Object
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
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
    <Head title="Histórico da Consulta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Histórico da Consulta
                </h2>
                <Link
                    :href="route('appointments.show', appointment.id)"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center gap-2"
                >
                    <ArrowLeftIcon class="h-5 w-5" />
                    Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Informações da Consulta -->
                        <div class="mb-8 border-b pb-4">
                            <div class="flex items-center gap-2 text-gray-600 mb-2">
                                <UserIcon class="h-5 w-5" />
                                <span class="font-medium">Paciente:</span>
                                {{ appointment.patient.name }}
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <ClockIcon class="h-5 w-5" />
                                <span class="font-medium">Data:</span>
                                {{ formatDate(appointment.date_time) }}
                            </div>
                        </div>

                        <!-- Timeline do Histórico -->
                        <div class="space-y-6">
                            <div v-if="appointment.histories.length === 0" class="text-center py-8 text-gray-500">
                                Nenhuma alteração registrada.
                            </div>
                            <div v-else class="flow-root">
                                <ul class="-mb-8">
                                    <li v-for="(history, historyIdx) in appointment.histories" :key="history.id">
                                        <div class="relative pb-8">
                                            <span v-if="historyIdx !== appointment.histories.length - 1" 
                                                  class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" 
                                                  aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                                        <DocumentTextIcon class="h-5 w-5 text-gray-500" />
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div>
                                                        <div class="text-sm">
                                                            <span class="font-medium text-gray-900">
                                                                {{ history.user.name }}
                                                            </span>
                                                            alterou o status para
                                                            <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(history.status)}`">
                                                                {{ history.status }}
                                                            </span>
                                                        </div>
                                                        <p class="mt-0.5 text-sm text-gray-500">
                                                            {{ formatDate(history.created_at) }}
                                                        </p>
                                                    </div>
                                                    <div v-if="history.notes" class="mt-2 text-sm text-gray-700">
                                                        {{ history.notes }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>