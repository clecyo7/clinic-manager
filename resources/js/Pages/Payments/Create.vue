<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { CurrencyDollarIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    appointment: Object
});

const form = useForm({
    appointment_id: props.appointment.id,
    amount: '',
    payment_method: '',
    payment_date: new Date().toISOString().split('T')[0],
    status: 'pending'
});

const submit = () => {
    form.post(route('payments.store'), {
        preserveScroll: true
    });
};
</script>

<template>
    <Head title="Registrar Pagamento" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Registrar Pagamento - {{ appointment.patient.name }}
                </h2>
                <Link
                    :href="route('appointments.show', appointment.id)"
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
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="amount" value="Valor" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <CurrencyDollarIcon class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <TextInput
                                        id="amount"
                                        type="number"
                                        step="0.01"
                                        class="pl-10 block w-full"
                                        v-model="form.amount"
                                        required
                                    />
                                </div>
                                <InputError :message="form.errors.amount" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="payment_method" value="Forma de Pagamento" />
                                <select
                                    id="payment_method"
                                    v-model="form.payment_method"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="">Selecione a forma de pagamento</option>
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="cartao_credito">Cartão de Crédito</option>
                                    <option value="cartao_debito">Cartão de Débito</option>
                                    <option value="pix">PIX</option>
                                    <option value="transferencia">Transferência</option>
                                </select>
                                <InputError :message="form.errors.payment_method" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="payment_date" value="Data do Pagamento" />
                                <TextInput
                                    id="payment_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.payment_date"
                                    required
                                />
                                <InputError :message="form.errors.payment_date" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{ form.processing ? 'Registrando...' : 'Registrar Pagamento' }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>