<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';

interface ProfileCard {
    id: number;
    name: string;
    age: number;
    bio_excerpt: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{
    users: {
        data: ProfileCard[];
        links: PaginationLink[];
    };
}>();

function message(id: number): void {
    router.post('/conversations', { recipient_id: id });
}
</script>

<template>
    <Head title="Browse" />

    <div class="p-4">
        <h1 class="mb-4 text-xl font-semibold">Browse profiles</h1>

        <p v-if="users.data.length === 0" class="text-sm text-gray-600">
            No other profiles yet.
        </p>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="user in users.data"
                :key="user.id"
                class="rounded-lg border border-gray-200 p-4"
            >
                <h2 class="text-lg font-medium">{{ user.name }}, {{ user.age }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ user.bio_excerpt }}</p>
                <div class="mt-4 flex gap-4">
                    <Link :href="`/profiles/${user.id}`" class="text-sm underline">
                        View profile
                    </Link>
                    <button type="button" class="text-sm underline" @click="message(user.id)">
                        Message
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <template v-for="link in users.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="rounded border border-gray-200 px-3 py-1 text-sm"
                    :class="{ 'bg-gray-100 font-medium': link.active }"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded border border-gray-100 px-3 py-1 text-sm text-gray-400"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
