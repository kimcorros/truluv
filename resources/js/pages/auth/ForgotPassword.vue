<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseInput from '@/components/base/BaseInput.vue';

defineOptions({
    layout: {
        title: 'Forgot password',
        description: 'Enter your email and we will send you a reset link.',
    },
});

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

function submit(): void {
    form.post('/forgot-password');
}
</script>

<template>
    <Head title="Forgot password" />

    <div
        v-if="status"
        class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-2 text-center text-sm text-emerald-400"
    >
        {{ status }}
    </div>

    <form class="space-y-5" @submit.prevent="submit">
        <BaseInput
            v-model="form.email"
            label="Email"
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            :error="form.errors.email"
            required
        />

        <BaseButton type="submit" block size="lg" :loading="form.processing">
            Email password reset link
        </BaseButton>
    </form>

    <p class="mt-6 text-center text-sm text-zinc-400">
        Return to
        <Link href="/login" class="font-semibold text-gradient">log in</Link>
    </p>
</template>
