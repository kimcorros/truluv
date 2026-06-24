<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Heart } from '@lucide/vue';

defineProps<{
    title?: string;
    description?: string;
}>();

// Decorative faces for the brand panel (free randomuser.me portrait set).
const faces = [
    'https://randomuser.me/api/portraits/women/65.jpg',
    'https://randomuser.me/api/portraits/men/32.jpg',
    'https://randomuser.me/api/portraits/women/44.jpg',
];
</script>

<template>
    <div class="grid min-h-svh bg-zinc-950 text-zinc-100 lg:grid-cols-2">
        <!-- Brand panel -->
        <div class="relative hidden overflow-hidden lg:block">
            <div class="absolute inset-0 bg-luv-gradient"></div>
            <div class="absolute inset-0 bg-zinc-950/40"></div>
            <div
                class="pointer-events-none absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-white/10 blur-3xl"
            ></div>

            <div class="relative flex h-full flex-col justify-between p-12">
                <Link href="/" class="flex items-center gap-2">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 backdrop-blur"
                    >
                        <Heart class="h-5 w-5 text-white" fill="currentColor" />
                    </span>
                    <span class="text-xl font-bold text-white">TruLuv</span>
                </Link>

                <div>
                    <h2 class="max-w-sm text-4xl font-bold leading-tight text-white">
                        Find your kind of love.
                    </h2>
                    <p class="mt-4 max-w-sm text-white/80">
                        Join millions of singles making real connections every day.
                    </p>

                    <div class="mt-8 flex items-center gap-3">
                        <div class="flex -space-x-3">
                            <img
                                v-for="face in faces"
                                :key="face"
                                :src="face"
                                alt=""
                                class="h-10 w-10 rounded-full object-cover ring-2 ring-white/70"
                            />
                        </div>
                        <p class="text-sm text-white/80">
                            <span class="font-semibold text-white">2.4M+</span> singles
                            online now
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form panel -->
        <div class="relative flex items-center justify-center p-6 sm:p-10">
            <div class="pointer-events-none absolute inset-0 luv-aura"></div>
            <div class="relative w-full max-w-sm">
                <Link
                    href="/"
                    class="mb-8 flex items-center justify-center gap-2 lg:hidden"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-luv-gradient"
                    >
                        <Heart class="h-5 w-5 text-white" fill="currentColor" />
                    </span>
                    <span class="text-xl font-bold">
                        Tru<span class="text-gradient">Luv</span>
                    </span>
                </Link>

                <div class="mb-8 text-center lg:text-left">
                    <h1 v-if="title" class="text-2xl font-bold text-white">
                        {{ title }}
                    </h1>
                    <p v-if="description" class="mt-2 text-sm text-zinc-400">
                        {{ description }}
                    </p>
                </div>

                <slot />

                <div
                    class="mt-8 flex items-center justify-center gap-3 text-xs text-zinc-500"
                >
                    <Link href="/terms" class="transition hover:text-zinc-300">
                        Terms &amp; Conditions
                    </Link>
                    <span class="text-zinc-700">&bull;</span>
                    <Link href="/privacy" class="transition hover:text-zinc-300">
                        Privacy Policy
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
