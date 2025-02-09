<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { CurrencyDollarIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { ref } from 'vue';
import { formatCurrency, formatDate } from '@/utils/format';

const props = defineProps({
    payments: Array,
    statistics: Object
});

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

const deleteForm = useForm({});

// Remove this old function
// const deletePayment = (paymentId) => {
//     if (confirm('Tem certeza que deseja excluir este pagamento?')) {
//         deleteForm.delete(route('payments.destroy', paymentId));
//     }
// };

// Keep only these delete-related functions
const showDeleteModal = ref(false);
const paymentToDelete = ref(null);

const confirmDelete = (payment) => {
    paymentToDelete.value = payment;
    showDeleteModal.value = true;
};

const handleDelete = () => {
    if (paymentToDelete.value) {
        deleteForm.delete(route('payments.destroy', paymentToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                paymentToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Pagamentos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Pagamentos
                </h2>
                <!-- Botão será exibido apenas na listagem de consultas -->
                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.appointment"
                        :href="route('appointments.payments.create', $page.props.appointment.id)"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                    >
                        <PlusIcon class="h-5 w-5 mr-2" />
                        Registrar Pagamento
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="payments.length === 0" class="text-center py-8 text-gray-500">
                            Nenhum pagamento registrado.
                        </div>
                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Paciente
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Valor
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Método
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="payment in payments" :key="payment.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ payment?.appointment?.patient?.name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ formatDate(payment.payment_date) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ formatCurrency(payment.amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ getPaymentMethodLabel(payment.payment_method) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(payment.status)}`">
                                                {{ payment.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-3">
                                                <Link :href="route('payments.show', payment.id)" 
                                                      class="text-indigo-600 hover:text-indigo-900">
                                                    Visualizar
                                                </Link>
                                                <Link :href="route('payments.edit', payment.id)"
                                                      class="text-blue-600 hover:text-blue-900">
                                                      <PencilIcon class="h-5 w-5" />
                                                  </Link>
                                                  <button @click="confirmDelete(payment)"
                                                          class="text-red-600 hover:text-red-900">
                                                      <TrashIcon class="h-5 w-5" />
                                                  </button>
                                              </div>
                                          </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    
    <!-- Add this at the end of the template, just before closing AuthenticatedLayout -->
    <ConfirmationModal
        :show="showDeleteModal"
        title="Confirmar Exclusão"
        message="Tem certeza que deseja excluir este pagamento? Esta ação não pode ser desfeita."
        @close="showDeleteModal = false"
        @confirm="handleDelete"
    />
</template>