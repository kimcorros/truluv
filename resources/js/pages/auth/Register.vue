<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseInput from '@/components/base/BaseInput.vue';
import BaseSelect from '@/components/base/BaseSelect.vue';

defineOptions({
    layout: {
        title: 'Create your account',
        description: 'It only takes a minute to start meeting people.',
    },
});

const genderOptions = [
    { value: '', label: 'Prefer not to say' },
    { value: 'female', label: 'Woman' },
    { value: 'male', label: 'Man' },
    { value: 'non-binary', label: 'Non-binary' },
    { value: 'other', label: 'Other' },
];

const form = useForm({
    name: '',
    email: '',
    gender: '',
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Register" />

    <form class="space-y-5" @submit.prevent="submit">
        <BaseInput
            v-model="form.name"
            label="Name"
            placeholder="Your name"
            autocomplete="name"
            :error="form.errors.name"
            required
        />
        <BaseInput
            v-model="form.email"
            label="Email"
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            :error="form.errors.email"
            required
        />
        <BaseSelect
            v-model="form.gender"
            label="I am a"
            :options="genderOptions"
            :error="form.errors.gender"
        />
        <BaseInput
            v-model="form.password"
            label="Password"
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
            Create account
        </BaseButton>
    </form>

    <p class="mt-6 text-center text-sm text-zinc-400">
        Already have an account?
        <Link href="/login" class="font-semibold text-gradient">Log in</Link>
    </p>
</template>
