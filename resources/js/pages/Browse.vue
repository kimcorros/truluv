<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Heart, LayoutGrid, List, MessageCircle } from '@lucide/vue';
import { onMounted, ref } from 'vue';
import GenderIcon from '@/components/GenderIcon.vue';
import BaseAvatar from '@/components/base/BaseAvatar.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseCard from '@/components/base/BaseCard.vue';

interface ProfileCard {
    id: number;
    name: string;
    age: number;
    gender: string | null;
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

type View = 'grid' | 'list';
const view = ref<View>('grid');

onMounted(() => {
    const saved = localStorage.getItem('browseView');
    if (saved === 'grid' || saved === 'list') {
        view.value = saved;
    }
});

function setView(value: View): void {
    view.value = value;
    localStorage.setItem('browseView', value);
}

function message(id: number): void {
    router.post('/conversations', { recipient_id: id });
}
</script>

<template>
    <Head title="Browse" />

    <div class="mx-auto w-full max-w-6xl p-4 sm:p-6">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Discover people</h1>
                <p class="mt-1 text-sm text-zinc-400">
                    Real members near you. Say hello to someone new.
                </p>
            </div>

            <!-- View toggle -->
            <div class="flex shrink-0 rounded-full border border-white/10 bg-white/5 p-1">
                <button
                    type="button"
                    class="flex h-8 w-8 items-center justify-center rounded-full transition"
                    :class="
                        view === 'grid'
                            ? 'bg-luv-gradient text-white'
                            : 'text-zinc-400 hover:text-white'
                    "
                    title="Grid view"
                    @click="setView('grid')"
                >
                    <LayoutGrid class="h-4 w-4" />
                </button>
                <button
                    type="button"
                    class="flex h-8 w-8 items-center justify-center rounded-full transition"
                    :class="
                        view === 'list'
                            ? 'bg-luv-gradient text-white'
                            : 'text-zinc-400 hover:text-white'
                    "
                    title="List view"
                    @click="setView('list')"
                >
                    <List class="h-4 w-4" />
                </button>
            </div>
        </div>

        <p v-if="users.data.length === 0" class="text-sm text-zinc-400">
            No other profiles yet.
        </p>

        <!-- Grid view -->
        <div
            v-else-if="view === 'grid'"
            class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
        >
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
                            <p
                                class="flex items-center gap-2 text-lg font-semibold text-white"
                            >
                                {{ user.name }}, {{ user.age }}
                                <GenderIcon :gender="user.gender" />
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

        <!-- List view -->
        <div v-else class="space-y-3">
            <BaseCard
                v-for="user in users.data"
                :key="user.id"
                hover
                class="flex flex-col gap-4 sm:flex-row sm:items-center"
            >
                <Link
                    :href="`/profiles/${user.id}`"
                    class="flex min-w-0 flex-1 items-center gap-4"
                >
                    <BaseAvatar :src="user.photo" :alt="user.name" size="lg" />
                    <div class="min-w-0">
                        <p
                            class="flex items-center gap-2 text-lg font-semibold text-white"
                        >
                            {{ user.name }}, {{ user.age }}
                            <GenderIcon :gender="user.gender" />
                        </p>
                        <p class="line-clamp-1 text-sm text-zinc-400">
                            {{ user.bio_excerpt }}
                        </p>
                    </div>
                </Link>
                <div class="flex shrink-0 gap-2">
                    <BaseButton
                        :href="`/profiles/${user.id}`"
                        variant="outline"
                        size="sm"
                    >
                        View
                    </BaseButton>
                    <BaseButton size="sm" @click="message(user.id)">
                        <MessageCircle class="h-4 w-4" />
                        Message
                    </BaseButton>
                </div>
            </BaseCard>
        </div>

        <div v-if="users.links.length > 3" class="mt-8 flex flex-wrap gap-2">
            <template v-for="link in users.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="rounded-lg border border-white/10 px-3 py-1.5 text-sm text-zinc-300 transition hover:bg-white/5"
                    :class="{
                        'bg-luv-gradient !border-transparent text-white':
                            link.active,
                    }"
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
