<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/base/BaseButton.vue';

defineOptions({
    layout: {
        title: 'Verify your email',
        description: 'We sent a verification link to your inbox.',
    },
});

defineProps<{
    status?: string;
}>();

const form = useForm({});

function resend(): void {
    form.post('/email/verification-notification');
}
</script>

<template>
    <Head title="Email verification" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-2 text-center text-sm text-emerald-400"
    >
        A new verification link has been sent to your email address.
    </div>

    <div class="space-y-5 text-center">
        <BaseButton
            block
            size="lg"
            variant="secondary"
            :loading="form.processing"
            @click="resend"
        >
            Resend verification email
        </BaseButton>

        <Link
            href="/logout"
            method="post"
            as="button"
            class="text-sm text-zinc-400 transition hover:text-white"
        >
            Log out
        </Link>
    </div>
</template>
