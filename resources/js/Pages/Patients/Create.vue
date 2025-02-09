<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    birth_date: '',
    gender: '',
    address: {
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
        zip_code: ''
    },
    profession: '',
    marital_status: '',
    emergency_contact: ''
});

const submit = () => {
    form.post(route('patients.store'));
};
</script>

<template>
    <Head title="Novo Paciente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Paciente</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Nome" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <!-- Add these fields after the name field and before the address section -->
                            <div>
                                <InputLabel for="birth_date" value="Data de Nascimento" />
                                <TextInput
                                    id="birth_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.birth_date"
                                    required
                                />
                                <InputError :message="form.errors.birth_date" class="mt-2" />
                            </div>
                            
                            <div>
                                <InputLabel for="gender" value="Gênero" />
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="">Selecione o gênero</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                    <option value="outro">Outro</option>
                                </select>
                                <InputError :message="form.errors.gender" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="email" value="E-mail" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="phone" value="Telefone" />
                                <TextInput
                                    id="phone"
                                    type="tel"
                                    class="mt-1 block w-full"
                                    v-model="form.phone"
                                    required
                                />
                                <InputError :message="form.errors.phone" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="profession" value="Profissão" />
                                    <TextInput
                                        id="profession"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.profession"
                                    />
                                    <InputError :message="form.errors.profession" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="marital_status" value="Estado Civil" />
                                    <select
                                        id="marital_status"
                                        v-model="form.marital_status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Selecione o estado civil</option>
                                        <option value="solteiro">Solteiro(a)</option>
                                        <option value="casado">Casado(a)</option>
                                        <option value="divorciado">Divorciado(a)</option>
                                        <option value="viuvo">Viúvo(a)</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                    <InputError :message="form.errors.marital_status" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="emergency_contact" value="Contato de Emergência" />
                                <TextInput
                                    id="emergency_contact"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.emergency_contact"
                                />
                                <InputError :message="form.errors.emergency_contact" class="mt-2" />
                            </div>

                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Endereço</h3>
                                
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="zip_code" value="CEP" />
                                        <TextInput
                                            id="zip_code"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.address.zip_code"
                                        />
                                        <InputError :message="form.errors['address.zip_code']" class="mt-2" />
                                    </div>
                                </div>
                            
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-2">
                                        <InputLabel for="street" value="Rua" />
                                        <TextInput
                                            id="street"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.address.street"
                                        />
                                        <InputError :message="form.errors['address.street']" class="mt-2" />
                                    </div>
                            
                                    <div>
                                        <InputLabel for="number" value="Número" />
                                        <TextInput
                                            id="number"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.address.number"
                                        />
                                        <InputError :message="form.errors['address.number']" class="mt-2" />
                                    </div>
                                </div>
                            
                                <div>
                                    <InputLabel for="complement" value="Complemento" />
                                    <TextInput
                                        id="complement"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.address.complement"
                                    />
                                    <InputError :message="form.errors['address.complement']" class="mt-2" />
                                </div>
                            
                                <div>
                                    <InputLabel for="neighborhood" value="Bairro" />
                                    <TextInput
                                        id="neighborhood"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.address.neighborhood"
                                    />
                                    <InputError :message="form.errors['address.neighborhood']" class="mt-2" />
                                </div>
                            
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="city" value="Cidade" />
                                        <TextInput
                                            id="city"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.address.city"
                                        />
                                        <InputError :message="form.errors['address.city']" class="mt-2" />
                                    </div>
                            
                                    <div>
                                        <InputLabel for="state" value="Estado" />
                                        <select
                                            id="state"
                                            v-model="form.address.state"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">Selecione o estado</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                        </select>
                                        <InputError :message="form.errors['address.state']" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Salvar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>