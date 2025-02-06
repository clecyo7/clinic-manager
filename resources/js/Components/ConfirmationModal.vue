<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

defineProps({
    show: Boolean,
    title: String,
    message: String,
});

defineEmits(['close', 'confirm']);
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6">
            <div class="text-lg font-medium text-gray-900">
                {{ title }}
            </div>

            <div class="mt-4 text-sm text-gray-600">
                {{ message }}
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <SecondaryButton @click="$emit('close')">
                    Cancelar
                </SecondaryButton>
                <DangerButton @click="$emit('confirm')">
                    Excluir
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>