<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { DocumentTextIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    patient: Object,
    appointments: Array
});

const form = useForm({
    patient_id: props.patient.id,
    appointment_id: '',
    diagnosis: '',
    treatment_plan: '',
    observations: '',
    attachments: []
});

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    form.attachments = files;
};

const submit = () => {
    form.post(route('medical-records.store'), {
        preserveScroll: true,
        forceFormData: true
    });
};
</script>

<template>
    <Head title="Novo Prontuário" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Novo Prontuário - {{ patient.name }}
                </h2>
                <Link
                    :href="route('patients.show', patient.id)"
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
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="appointment_id" value="Consulta" />
                                <select
                                    id="appointment_id"
                                    v-model="form.appointment_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="">Selecione a consulta</option>
                                    <option v-for="appointment in appointments" 
                                            :key="appointment.id" 
                                            :value="appointment.id"
                                    >
                                        {{ new Date(appointment.date_time).toLocaleString('pt-BR') }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.appointment_id" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="diagnosis" value="Histórico Clínico" />
                                <textarea
                                    id="diagnosis"
                                    v-model="form.diagnosis"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4"
                                    required
                                ></textarea>
                                <InputError :message="form.errors.diagnosis" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="treatment_plan" value="Evolução" />
                                <textarea
                                    id="treatment_plan"
                                    v-model="form.treatment_plan"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4"
                                    required
                                ></textarea>
                                <InputError :message="form.errors.treatment_plan" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="observations" value="Observações" />
                                <textarea
                                    id="observations"
                                    v-model="form.observations"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4"
                                ></textarea>
                                <InputError :message="form.errors.observations" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="attachments" value="Anexos" />
                                <input
                                    type="file"
                                    id="attachments"
                                    multiple
                                    @change="handleFileUpload"
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100"
                                />
                                <InputError :message="form.errors.attachments" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{ form.processing ? 'Salvando...' : 'Salvar Prontuário' }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>