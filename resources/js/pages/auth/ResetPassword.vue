<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseInput from '@/components/base/BaseInput.vue';

defineOptions({
    layout: {
        title: 'Reset password',
        description: 'Choose a new password for your account.',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules?: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Reset password" />

    <form class="space-y-5" @submit.prevent="submit">
        <BaseInput
            v-model="form.email"
            label="Email"
            type="email"
            autocomplete="email"
            :error="form.errors.email"
            readonly
        />
        <BaseInput
            v-model="form.password"
            label="New password"
            type="password"
            placeholder="••••••••"
            autocomplete="new-password"
            :error="form.errors.password"
            required
        />
        <BaseInput
            v-model="form.password_confirmation"
            label="Confirm password"
            type="password"
            placeholder="••••••••"
            autocomplete="new-password"
            :error="form.errors.password_confirmation"
            required
        />

        <BaseButton type="submit" block size="lg" :loading="form.processing">
            Reset password
        </BaseButton>
    </form>
</template>
