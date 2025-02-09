<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import AppointmentsChart from '@/Components/AppointmentsChart.vue';
import RevenueChart from '@/Components/RevenueChart.vue';
import { 
    UsersIcon, 
    UserGroupIcon,
    CalendarIcon,
    XCircleIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    BanknotesIcon,
    CurrencyDollarIcon,
    ClockIcon as PendingIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    statistics: Object
});


const stats = props.statistics;

const formatCurrency = (value) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value || 0);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Patients Stats -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pacientes</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <UsersIcon class="h-8 w-8 text-blue-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Total de Pacientes</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.patients.total }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <UserGroupIcon class="h-8 w-8 text-green-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Pacientes Ativos</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.patients.active }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <UserGroupIcon class="h-8 w-8 text-red-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Pacientes Inativos</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.patients.inactive }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments Stats -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Consultas</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <CalendarIcon class="h-8 w-8 text-indigo-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Total</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.appointments.total }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <CheckCircleIcon class="h-8 w-8 text-green-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Realizadas</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.appointments.completed }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <ExclamationCircleIcon class="h-8 w-8 text-yellow-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Pendentes</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.appointments.pending }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <XCircleIcon class="h-8 w-8 text-red-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Cancelados</h4>
                                    <p class="text-2xl font-semibold text-gray-900">{{ statistics.appointments.canceled }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Stats -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Financeiro</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <BanknotesIcon class="h-8 w-8 text-gray-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Total</h4>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ formatCurrency(statistics.payments?.total ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <CurrencyDollarIcon class="h-8 w-8 text-green-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Recebido</h4>
                                    <p class="text-2xl font-semibold text-green-600">
                                        {{ formatCurrency(statistics.payments?.paid ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <PendingIcon class="h-8 w-8 text-yellow-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Pendente</h4>
                                    <p class="text-2xl font-semibold text-yellow-600">
                                        {{ formatCurrency(statistics.payments?.pending ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex items-center">
                                <XCircleIcon class="h-8 w-8 text-red-500 mr-3" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Cancelado</h4>
                                    <p class="text-2xl font-semibold text-red-600">
                                        {{ formatCurrency(statistics.payments?.canceled ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Consultas por Mês</h3>
                        <div class="h-64">
                            <AppointmentsChart :data="stats.monthlyAppointments" />
                        </div>
                    </div>
    
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Receita por Mês</h3>
                        <div class="h-64">
                            <RevenueChart :data="stats.monthlyRevenues" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
