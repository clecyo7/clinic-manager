<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ArrowLeftIcon, DocumentTextIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    record: Object
});

const form = useForm({
    diagnosis: props.record.diagnosis,
    treatment_plan: props.record.treatment_plan,
    observations: props.record.observations,
    attachments: []
});

const submit = () => {
    form.put(route('medical-records.update', props.record.id));
};
</script>

<template>
    <Head title="Editar Prontuário" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <Link
                        :href="route('medical-records.index')"
                        class="flex items-center text-gray-500 hover:text-gray-700 mr-4"
                    >
                        <ArrowLeftIcon class="w-5 h-5 mr-2" />
                        Voltar
                    </Link>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Editar Prontuário
                    </h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="diagnosis" value="Histórico Clínico" />
                            <textarea
                                id="diagnosis"
                                v-model="form.diagnosis"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            ></textarea>
                            <InputError :message="form.errors.diagnosis" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="treatment_plan" value="Evolução" />
                            <textarea
                                id="treatment_plan"
                                v-model="form.treatment_plan"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            ></textarea>
                            <InputError :message="form.errors.treatment_plan" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="observations" value="Observações" />
                            <textarea
                                id="observations"
                                v-model="form.observations"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                            <InputError :message="form.errors.observations" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="attachments" value="Novos Anexos" />
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

                        <div v-if="record.attachments && record.attachments.length > 0">
                            <h3 class="text-sm font-medium text-gray-700">Anexos Existentes</h3>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <a
                                    v-for="attachment in record.attachments"
                                    :key="attachment.id"
                                    :href="attachment.url"
                                    target="_blank"
                                    class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100"
                                >
                                    <DocumentTextIcon class="h-6 w-6 text-gray-400 mr-2" />
                                    <span class="text-sm text-gray-900">{{ attachment.name }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link
                                :href="route('medical-records.index')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>