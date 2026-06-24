<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, MessageCircle } from '@lucide/vue';
import GenderIcon from '@/components/GenderIcon.vue';
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

function message(): void {
    router.post('/conversations', { recipient_id: props.profile.id });
}
</script>

<template>
    <Head :title="profile.name" />

    <div class="mx-auto w-full max-w-2xl p-4 sm:p-6">
        <Link
            href="/browse"
            class="mb-6 inline-flex items-center gap-1.5 text-sm text-zinc-400 transition hover:text-white"
        >
            <ArrowLeft class="h-4 w-4" />
            Back to browse
        </Link>

        <BaseCard :padded="false" class="overflow-hidden">
            <div class="relative">
                <img
                    v-if="profile.photo"
                    :src="profile.photo"
                    :alt="profile.name"
                    class="aspect-[16/10] w-full object-cover"
                />
                <div
                    v-else
                    class="flex aspect-[16/10] w-full items-center justify-center bg-white/5 text-zinc-600"
                >
                    No photo
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent"
                ></div>
                <div class="absolute inset-x-0 bottom-0 p-6">
                    <h1
                        class="flex items-center gap-3 text-3xl font-bold text-white"
                    >
                        {{ profile.name }}, {{ profile.age }}
                        <GenderIcon :gender="profile.gender" show-label />
                    </h1>
                </div>
            </div>

            <div class="p-6">
                <p class="whitespace-pre-line text-zinc-300">{{ profile.bio }}</p>

                <BaseButton class="mt-6" @click="message">
                    <MessageCircle class="h-4 w-4" />
                    Message {{ profile.name }}
                </BaseButton>
            </div>
        </BaseCard>
    </div>
</template>
