<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { CurrencyDollarIcon, CalendarIcon, UserIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    payment: Object
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        paid: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getPaymentMethodLabel = (method) => {
    const methods = {
        dinheiro: 'Dinheiro',
        cartao_credito: 'Cartão de Crédito',
        cartao_debito: 'Cartão de Débito',
        pix: 'PIX',
        transferencia: 'Transferência'
    };
    return methods[method] || method;
};
</script>

<template>
    <Head title="Detalhes do Pagamento" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detalhes do Pagamento
                </h2>
                <Link
                    :href="route('payments.index')"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                >
                    Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <UserIcon class="h-5 w-5 text-gray-400" />
                                    <span class="font-medium">Paciente:</span>
                                    {{ payment.appointment.patient.name }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <CalendarIcon class="h-5 w-5 text-gray-400" />
                                    <span class="font-medium">Data do Pagamento:</span>
                                    {{ formatDate(payment.payment_date) }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <CurrencyDollarIcon class="h-5 w-5 text-gray-400" />
                                    <span class="font-medium">Valor:</span>
                                    {{ formatCurrency(payment.amount) }}
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="font-medium">Método de Pagamento:</span>
                                    <div class="mt-1">
                                        {{ getPaymentMethodLabel(payment.payment_method) }}
                                    </div>
                                </div>
                                <div>
                                    <span class="font-medium">Status:</span>
                                    <div class="mt-1">
                                        <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(payment.status)}`">
                                            {{ payment.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>