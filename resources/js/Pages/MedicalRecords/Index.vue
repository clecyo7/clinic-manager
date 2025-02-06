<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { DocumentTextIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { ref } from 'vue';  // Adicionando a importação do ref

const props = defineProps({
    records: {
        type: Array,
        default: () => []
    },
    patient: {
        type: Object,
        default: null
    }
});

const showDeleteModal = ref(false);
const recordToDelete = ref(null);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const confirmDelete = (record) => {
    recordToDelete.value = record;
    showDeleteModal.value = true;
};

const deleteForm = useForm({});

const handleDelete = () => {
    if (recordToDelete.value) {
        deleteForm.delete(route('medical-records.destroy', recordToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                recordToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Prontuários" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Prontuários
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="medicalRecords.length === 0" class="text-center py-8 text-gray-500">
                            Nenhum prontuário registrado.
                        </div>
                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data da Consulta
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Diagnóstico
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="record in medicalRecords" :key="record.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ formatDate(record.appointment.date_time) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 line-clamp-2">
                                                {{ record.diagnosis }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-3">
                                                <Link :href="route('medical-records.show', record.id)" 
                                                      class="text-indigo-600 hover:text-indigo-900">
                                                    Visualizar
                                                </Link>
                                                <Link :href="route('medical-records.edit', record.id)"
                                                      class="text-blue-600 hover:text-blue-900">
                                                    <PencilIcon class="h-5 w-5" />
                                                </Link>
                                                <button @click="confirmDelete(record)"
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

        <ConfirmationModal
            :show="showDeleteModal"
            title="Confirmar Exclusão"
            message="Tem certeza que deseja excluir este prontuário? Esta ação não pode ser desfeita."
            @close="showDeleteModal = false"
            @confirm="handleDelete"
        />
    </AuthenticatedLayout>
</template>