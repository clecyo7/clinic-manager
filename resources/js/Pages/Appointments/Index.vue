<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { 
    PencilIcon, 
    EyeIcon, 
    TrashIcon,
    PlusCircleIcon,
    MagnifyingGlassIcon,
    FunnelIcon,
    CalendarIcon,
    UserIcon
} from '@heroicons/vue/24/outline';
import { XCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/solid';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const props = defineProps({
    appointments: {
        type: Array,
        default: () => []
    }
});

const search = ref('');
const statusFilter = ref('all');
const dateFilter = ref('all');
const confirmingDeletion = ref(false);
const appointmentToDelete = ref(null);

const filteredAppointments = computed(() => {
    if (!props.appointments) return [];
    
    return props.appointments.filter(appointment => {
        if (!appointment || !appointment.patient) return false;
        
        const matchesSearch = appointment.patient.name.toLowerCase().includes(search.value.toLowerCase());
        const matchesStatus = statusFilter.value === 'all' || appointment.status === statusFilter.value;
        const today = new Date();
        const appointmentDate = new Date(appointment.date_time);

        let matchesDate = true;
        if (dateFilter.value === 'today') {
            matchesDate = appointmentDate.toDateString() === today.toDateString();
        } else if (dateFilter.value === 'week') {
            const weekAhead = new Date(today);
            weekAhead.setDate(today.getDate() + 7);
            matchesDate = appointmentDate >= today && appointmentDate <= weekAhead;
        } else if (dateFilter.value === 'month') {
            matchesDate = appointmentDate.getMonth() === today.getMonth() && 
                         appointmentDate.getFullYear() === today.getFullYear();
        }

        return matchesSearch && matchesStatus && matchesDate;
    });
});

const formatDateTime = (dateTime) => {
    const date = new Date(dateTime);
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

const getStatusColor = (status) => {
    const colors = {
        pendente: 'bg-yellow-100 text-yellow-800',
        concluida: 'bg-green-100 text-green-800',
        cancelada: 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const confirmDelete = (appointment) => {
    appointmentToDelete.value = appointment;
    confirmingDeletion.value = true;
};

const deleteAppointment = () => {
    router.delete(route('appointments.destroy', appointmentToDelete.value.id), {
        onSuccess: () => {
            confirmingDeletion.value = false;
            appointmentToDelete.value = null;
        },
        onError: (errors) => {
            confirmingDeletion.value = false;
            appointmentToDelete.value = null;
        },
        preserveScroll: true,
        preserveState: true
    });
};
</script>

<template>
    <Head title="Consultas" />

    <AuthenticatedLayout>
        <!-- Update flash message handling -->
        <div v-if="$page?.props?.flash?.error" class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ $page?.props?.flash?.error }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="$page?.props?.flash?.message" class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <CheckCircleIcon class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ $page?.props?.flash?.message }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="page.props.flash?.error" class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ page.props.flash.error }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="page.props.flash?.message" class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <CheckCircleIcon class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ $page?.props?.flash?.message }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Consultas</h2>
                <Link
                    :href="route('appointments.create')"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 inline-flex items-center gap-2"
                >
                    <PlusCircleIcon class="h-5 w-5" />
                    Nova Consulta
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <TextInput
                                        v-model="search"
                                        type="text"
                                        placeholder="Buscar paciente..."
                                        class="pl-10"
                                    />
                                </div>

                                <div class="flex items-center gap-2">
                                    <FunnelIcon class="h-5 w-5 text-gray-400" />
                                    <select
                                        v-model="statusFilter"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="all">Todos os status</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="concluida">Concluída</option>
                                        <option value="cancelada">Cancelada</option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-2">
                                    <CalendarIcon class="h-5 w-5 text-gray-400" />
                                    <select
                                        v-model="dateFilter"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="all">Todas as datas</option>
                                        <option value="today">Hoje</option>
                                        <option value="week">Próxima semana</option>
                                        <option value="month">Este mês</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Update the v-if and v-for to use filteredAppointments -->
                        <div v-if="filteredAppointments.length === 0" class="text-center py-8 text-gray-500">
                            Nenhuma consulta encontrada.
                        </div>
                        <table v-else class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Paciente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data e Hora
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="appointment in filteredAppointments" :key="appointment.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ appointment?.patient?.name || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ formatDateTime(appointment.date_time) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="`px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(appointment.status)}`">
                                            {{ appointment.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                        <Link
                                            :href="route('appointments.edit', appointment.id)"
                                            class="text-indigo-600 hover:text-indigo-900 inline-flex items-center gap-1"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                            Editar
                                        </Link>
                                        <Link
                                            :href="route('appointments.show', appointment.id)"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1"
                                        >
                                            <EyeIcon class="h-4 w-4" />
                                            Ver
                                        </Link>
                                        <button
                                            @click="confirmDelete(appointment)"
                                            class="text-red-600 hover:text-red-900 inline-flex items-center gap-1"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                            Excluir
                                        </button>
                                    </td>
                                    <!-- Modal de Confirmação de Exclusão -->
                                    <Modal :show="confirmingAppointmentDeletion" @close="confirmingAppointmentDeletion = false">
                                        <div class="p-6">
                                            <h2 class="text-lg font-medium text-gray-900">
                                                Confirmar exclusão
                                            </h2>
                                    
                                            <p class="mt-1 text-sm text-gray-600">
                                                Tem certeza que deseja excluir esta consulta? Esta ação não pode ser desfeita.
                                            </p>
                                    
                                            <div class="mt-6 flex justify-end space-x-4">
                                                <SecondaryButton @click="confirmingAppointmentDeletion = false">
                                                    Cancelar
                                                </SecondaryButton>
                                    
                                                <DangerButton
                                                    @click="deleteAppointment"
                                                    class="ml-3"
                                                >
                                                    Excluir Consulta
                                                </DangerButton>
                                            </div>
                                        </div>
                                    </Modal>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- Delete Confirmation Modal -->
    <Modal :show="confirmingDeletion" @close="confirmingDeletion = false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Confirmar exclusão
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Tem certeza que deseja excluir esta consulta? Esta ação não pode ser desfeita.
            </p>

            <div class="mt-6 flex justify-end space-x-4">
                <SecondaryButton @click="confirmingDeletion = false">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    @click="deleteAppointment"
                    class="ml-3"
                >
                    Excluir Consulta
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>