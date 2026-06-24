<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseInput from '@/components/base/BaseInput.vue';

defineOptions({
    layout: {
        title: 'Welcome back',
        description: 'Log in to keep the conversation going.',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit(): void {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Log in" />

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

        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-zinc-300">Password</span>
                <Link
                    v-if="canResetPassword"
                    href="/forgot-password"
                    class="text-xs text-pink-400 transition hover:text-pink-300"
                >
                    Forgot password?
                </Link>
            </div>
            <BaseInput
                v-model="form.password"
                type="password"
                placeholder="••••••••"
                :error="form.errors.password"
            />
        </div>

        <label class="flex items-center gap-2 text-sm text-zinc-400">
            <input
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 rounded border-white/20 bg-white/5 text-pink-500 focus:ring-pink-400/50"
            />
            Remember me
        </label>

        <BaseButton type="submit" block size="lg" :loading="form.processing">
            Log in
        </BaseButton>
    </form>

    <p class="mt-6 text-center text-sm text-zinc-400">
        New to TruLuv?
        <Link href="/register" class="font-semibold text-gradient">
            Create an account
        </Link>
    </p>
</template>
