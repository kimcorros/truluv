<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    profile: {
        id: number;
        name: string;
        age: number;
        gender: string | null;
        bio: string;
    };
}>();

function message(): void {
    router.post('/conversations', { recipient_id: props.profile.id });
}
</script>

<template>
    <Head :title="profile.name" />

    <div class="max-w-lg p-4">
        <Link href="/browse" class="text-sm underline">&larr; Back to browse</Link>

        <h1 class="mt-4 text-2xl font-semibold">{{ profile.name }}, {{ profile.age }}</h1>
        <p v-if="profile.gender" class="text-sm text-gray-600">{{ profile.gender }}</p>

        <p class="mt-4 whitespace-pre-line">{{ profile.bio }}</p>

        <button
            type="button"
            class="mt-6 rounded bg-gray-900 px-4 py-2 text-sm text-white"
            @click="message"
        >
            Message {{ profile.name }}
        </button>
    </div>
</template>
