<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    patient: Object
});

// Formatar data
const formatDate = (date) => {
    if (!date) return 'Não informado';
    return new Date(date).toLocaleDateString('pt-BR', {
        timeZone: 'UTC'
    });
};

// Formatar gênero
const getGenderLabel = (gender) => {
    const labels = {
        'masculino': 'Masculino',
        'feminino': 'Feminino',
        'outro': 'Outro'
    };
    return labels[gender] || gender;
};

// Função para baixar o PDF
// Update the baixarPdf function to include patient ID
const baixarPdf = () => {
    if (!props.patient || !props.patient.id) {
        console.error("ID do paciente não encontrado.");
        return;
    }
    window.open(route('gerar.pdf', { id: props.patient.id }), '_blank');
};


</script>

<template>
    <Head title="Detalhes do Paciente" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalhes do Paciente</h2>
                <Link
                    :href="route('patients.index')"
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
                        <div class="flex justify-end">
                           <button
    @click="baixarPdf"
    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
>
    Baixar Relatório PDF
</button>

                            
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Informações Pessoais</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Nome</label>
                                        <p class="mt-1">{{ patient.name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Data de Nascimento</label>
                                        <p class="mt-1">{{ formatDate(patient.birth_date) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Gênero</label>
                                        <p class="mt-1">{{ getGenderLabel(patient.gender) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Profissão</label>
                                        <p class="mt-1">{{ patient.profession || 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Estado Civil</label>
                                        <p class="mt-1 capitalize">{{ patient.marital_status || 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Contato</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Telefone</label>
                                        <p class="mt-1">{{ patient.phone }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">E-mail</label>
                                        <p class="mt-1">{{ patient.email || 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Endereço</label>
                                        <div v-if="patient.address" class="mt-1">
                                            <p>{{ patient.address.street }}, {{ patient.address.number }}</p>
                                            <p v-if="patient.address.complement">{{ patient.address.complement }}</p>
                                            <p>{{ patient.address.neighborhood }}</p>
                                            <p>{{ patient.address.city }} - {{ patient.address.state }}</p>
                                            <p>CEP: {{ patient.address.zip_code }}</p>
                                        </div>
                                        <p v-else class="mt-1">Não informado</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Contato de Emergência</label>
                                        <p class="mt-1">{{ patient.emergency_contact || 'Não informado' }}</p>
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
