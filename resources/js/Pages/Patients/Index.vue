<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { PlusCircleIcon, PencilIcon, TrashIcon, EyeIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    patients: Array
});

const confirmingPatientDeletion = ref(false);
const patientToDelete = ref(null);

const confirmPatientDeletion = (patient) => {
    patientToDelete.value = patient;
    confirmingPatientDeletion.value = true;
};

const deletePatient = () => {
    router.delete(route('patients.destroy', patientToDelete.value.id), {
        onSuccess: () => {
            confirmingPatientDeletion.value = false;
            patientToDelete.value = null;
        },
    });
};

const restorePatient = (patient) => {
    router.post(route('patients.restore', patient.id));
};

const formatDate = (date) => {
    if (!date) return '';
    const dateObj = new Date(date);
    return dateObj.toLocaleDateString('pt-BR', {
        timeZone: 'UTC'
    });
};
</script>

<template>
    <Head title="Pacientes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Pacientes</h2>
                <Link
                    :href="route('patients.create')"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 inline-flex items-center gap-2"
                >
                    <PlusCircleIcon class="h-5 w-5" />
                    Novo Paciente
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gênero</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Nascimento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="patient in patients" :key="patient?.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ patient?.name || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ patient.phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ patient.gender }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(patient.birth_date) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                        <Link
                                            :href="route('patients.edit', patient.id)"
                                            class="text-indigo-600 hover:text-indigo-900 inline-flex items-center gap-1"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                            Editar
                                        </Link>
                                        <Link
                                            :href="route('patients.show', patient.id)"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1"
                                        >
                                            <EyeIcon class="h-4 w-4" />
                                            Ver
                                        </Link>
                                        <button
                                            v-if="!patient.deleted_at"
                                            @click="confirmPatientDeletion(patient)"
                                            class="text-red-600 hover:text-red-900 inline-flex items-center gap-1"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                            Inativar
                                        </button>
                                        <button
                                            v-else
                                            @click="restorePatient(patient)"
                                            class="text-green-600 hover:text-green-900 inline-flex items-center gap-1"
                                        >
                                            <ArrowPathIcon class="h-4 w-4" />
                                            Reativar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <Modal :show="confirmingPatientDeletion" @close="confirmingPatientDeletion = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Confirmar exclusão
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tem certeza que deseja excluir este paciente? Esta ação não pode ser desfeita.
                </p>

                <div class="mt-6 flex justify-end space-x-4">
                    <SecondaryButton @click="confirmingPatientDeletion = false">
                        Cancelar
                    </SecondaryButton>

                    <DangerButton
                        @click="deletePatient"
                        class="ml-3"
                    >
                        Excluir Paciente
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>