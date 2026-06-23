<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Heart, MessageCircle } from '@lucide/vue';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseCard from '@/components/base/BaseCard.vue';

interface ProfileCard {
    id: number;
    name: string;
    age: number;
    photo: string | null;
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

    <div class="mx-auto w-full max-w-6xl p-4 sm:p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Discover people</h1>
            <p class="mt-1 text-sm text-zinc-400">
                Real members near you. Say hello to someone new.
            </p>
        </div>

        <p v-if="users.data.length === 0" class="text-sm text-zinc-400">
            No other profiles yet.
        </p>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <BaseCard
                v-for="user in users.data"
                :key="user.id"
                :padded="false"
                hover
                class="overflow-hidden"
            >
                <Link :href="`/profiles/${user.id}`" class="block">
                    <div class="relative">
                        <img
                            v-if="user.photo"
                            :src="user.photo"
                            :alt="user.name"
                            class="aspect-[4/5] w-full object-cover"
                            loading="lazy"
                        />
                        <div
                            v-else
                            class="flex aspect-[4/5] w-full items-center justify-center bg-white/5"
                        >
                            <Heart class="h-10 w-10 text-white/20" />
                        </div>
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-transparent to-transparent"
                        ></div>
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <p class="text-lg font-semibold text-white">
                                {{ user.name }}, {{ user.age }}
                            </p>
                        </div>
                    </div>
                </Link>

                <div class="p-4">
                    <p class="line-clamp-2 min-h-10 text-sm text-zinc-400">
                        {{ user.bio_excerpt }}
                    </p>
                    <div class="mt-4 flex gap-2">
                        <BaseButton
                            :href="`/profiles/${user.id}`"
                            variant="outline"
                            size="sm"
                            block
                        >
                            View
                        </BaseButton>
                        <BaseButton size="sm" block @click="message(user.id)">
                            <MessageCircle class="h-4 w-4" />
                            Message
                        </BaseButton>
                    </div>
                </div>
            </BaseCard>
        </div>

        <div v-if="users.links.length > 3" class="mt-8 flex flex-wrap gap-2">
            <template v-for="link in users.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="rounded-lg border border-white/10 px-3 py-1.5 text-sm text-zinc-300 transition hover:bg-white/5"
                    :class="{ 'bg-luv-gradient !border-transparent text-white': link.active }"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded-lg border border-white/5 px-3 py-1.5 text-sm text-zinc-600"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
