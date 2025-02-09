<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    HeartIcon, 
    UserGroupIcon, 
    AcademicCapIcon, 
    ClockIcon 
} from '@heroicons/vue/24/outline';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bem-vindo" />

    <div class="min-h-screen bg-gray-50">
        <!-- Header/Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-2xl font-semibold text-indigo-600">PsiCloud</span>
                    </div>
                    
                    <div class="flex items-center">
                        <template v-if="canLogin">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="text-gray-700 hover:text-indigo-600 px-3 py-2"
                            >
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="text-gray-700 hover:text-indigo-600 px-3 py-2"
                                >
                                    Entrar
                                </Link>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-white">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block">Cuidando da sua</span>
                        <span class="block text-indigo-600">Saúde Mental</span>
                    </h1>
                    <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                        Oferecemos atendimento psicológico personalizado para ajudar você a alcançar bem-estar emocional e qualidade de vida.
                    </p>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <HeartIcon class="h-12 w-12 text-indigo-600" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Atendimento Humanizado</h3>
                        <p class="mt-2 text-gray-500">
                            Cuidado e atenção personalizada para cada paciente
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <UserGroupIcon class="h-12 w-12 text-indigo-600" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Terapia Individual e Grupo</h3>
                        <p class="mt-2 text-gray-500">
                            Diferentes modalidades de atendimento
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <AcademicCapIcon class="h-12 w-12 text-indigo-600" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Profissionais Qualificados</h3>
                        <p class="mt-2 text-gray-500">
                            Equipe especializada e constantemente atualizada
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <ClockIcon class="h-12 w-12 text-indigo-600" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Horários Flexíveis</h3>
                        <p class="mt-2 text-gray-500">
                            Agendamento adaptado à sua rotina
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-indigo-700">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">Pronto para começar?</span>
                    <span class="block text-indigo-200">Agende sua consulta hoje mesmo.</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        <div class="inline-flex rounded-md shadow">
                            <a
                                href="https://wa.me/5561982039671?text=Olá! Gostaria de agendar uma consulta."
                                target="_blank"
                                class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50"
                            >
                                Fale Conosco
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">
                    &copy; 2024 PsiCloud. Todos os direitos reservados.
                </p>
            </div>
        </footer>
    </div>
</template>