<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, MessageCircle, Sparkles, Star, X } from '@lucide/vue';
import { computed } from 'vue';
import GenderIcon from '@/components/GenderIcon.vue';
import BaseBadge from '@/components/base/BaseBadge.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseCard from '@/components/base/BaseCard.vue';

const props = defineProps<{
    profile: {
        id: number;
        name: string;
        age: number;
        gender: string | null;
        photo: string | null;
        bio: string;
    };
}>();

const genderLabels: Record<string, string> = {
    female: 'Woman',
    male: 'Man',
    'non-binary': 'Non-binary',
    other: 'Other',
};

const genderLabel = computed(() =>
    props.profile.gender
        ? (genderLabels[props.profile.gender] ?? props.profile.gender)
        : '',
);

function message(): void {
    router.post('/conversations', { recipient_id: props.profile.id });
}

function pass(): void {
    router.visit('/browse');
}
</script>

<template>
    <Head :title="profile.name" />

    <div class="mx-auto w-full max-w-xl p-4 sm:p-6">
        <Link
            href="/browse"
            class="mb-4 inline-flex items-center gap-1.5 text-sm text-zinc-400 transition hover:text-white"
        >
            <ArrowLeft class="h-4 w-4" />
            Back to browse
        </Link>

        <!-- Photo hero -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10">
            <img
                v-if="profile.photo"
                :src="profile.photo"
                :alt="profile.name"
                class="aspect-[4/5] w-full object-cover"
            />
            <div
                v-else
                class="flex aspect-[4/5] w-full items-center justify-center bg-white/5 text-zinc-600"
            >
                No photo
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/10 to-transparent"
            ></div>

            <div
                class="absolute left-4 top-4 flex items-center gap-1.5 rounded-full bg-zinc-950/50 px-3 py-1 text-xs font-medium text-white backdrop-blur"
            >
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                Active recently
            </div>

            <div class="absolute inset-x-0 bottom-0 p-6">
                <h1
                    class="flex items-center gap-2 text-3xl font-bold text-white drop-shadow"
                >
                    {{ profile.name }}, {{ profile.age }}
                    <GenderIcon :gender="profile.gender" />
                </h1>
                <p v-if="genderLabel" class="mt-1 text-sm text-zinc-300">
                    {{ genderLabel }}
                </p>
            </div>
        </div>

        <!-- Action bar (overlapping the photo, dating-app style) -->
        <div class="-mt-7 mb-2 flex items-center justify-center gap-5">
            <button
                type="button"
                class="flex h-14 w-14 items-center justify-center rounded-full border border-white/10 bg-zinc-900 text-zinc-300 shadow-xl transition hover:scale-105 hover:text-white"
                title="Pass"
                @click="pass"
            >
                <X class="h-6 w-6" />
            </button>
            <button
                type="button"
                class="flex h-16 w-16 items-center justify-center rounded-full bg-luv-gradient text-white shadow-xl transition hover:scale-105 hover:luv-glow"
                title="Message"
                @click="message"
            >
                <MessageCircle class="h-7 w-7" />
            </button>
            <button
                type="button"
                class="flex h-14 w-14 items-center justify-center rounded-full border border-white/10 bg-zinc-900 text-amber-300 shadow-xl transition hover:scale-105 hover:text-amber-200"
                title="Super like"
                @click="message"
            >
                <Star class="h-6 w-6" fill="currentColor" />
            </button>
        </div>

        <!-- About -->
        <BaseCard class="mt-4">
            <h2
                class="mb-2 flex items-center gap-2 text-xs font-semibold tracking-wide text-zinc-400 uppercase"
            >
                <Sparkles class="h-4 w-4 text-pink-400" />
                About {{ profile.name }}
            </h2>
            <p class="whitespace-pre-line text-zinc-200">{{ profile.bio }}</p>
        </BaseCard>

        <!-- Basics -->
        <BaseCard class="mt-4">
            <h2
                class="mb-3 text-xs font-semibold tracking-wide text-zinc-400 uppercase"
            >
                Basics
            </h2>
            <div class="flex flex-wrap gap-2">
                <BaseBadge variant="soft">{{ profile.age }} years old</BaseBadge>
                <BaseBadge v-if="genderLabel" variant="soft">
                    <GenderIcon :gender="profile.gender" />
                    {{ genderLabel }}
                </BaseBadge>
            </div>
        </BaseCard>

        <BaseButton class="mt-6" block size="lg" @click="message">
            <MessageCircle class="h-4 w-4" />
            Message {{ profile.name }}
        </BaseButton>
    </div>
</template>
