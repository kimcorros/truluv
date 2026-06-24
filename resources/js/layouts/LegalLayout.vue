<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Heart } from '@lucide/vue';
import { computed } from 'vue';
import AppFooter from '@/components/AppFooter.vue';

defineProps<{
    title?: string;
    updated?: string;
}>();

const page = usePage();
const isAuthed = computed(
    () => !!(page.props as { auth?: { user?: unknown } }).auth?.user,
);
</script>

<template>
    <div class="flex min-h-svh flex-col bg-zinc-950 text-zinc-100">
        <header
            class="sticky top-0 z-50 border-b border-white/5 bg-zinc-950/70 backdrop-blur-xl"
        >
            <div
                class="mx-auto flex h-16 max-w-3xl items-center justify-between px-4 sm:px-6"
            >
                <Link href="/" class="flex items-center gap-2">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-luv-gradient"
                    >
                        <Heart class="h-4 w-4 text-white" fill="currentColor" />
                    </span>
                    <span class="text-lg font-bold">
                        Tru<span class="text-gradient">Luv</span>
                    </span>
                </Link>
                <Link
                    :href="isAuthed ? '/browse' : '/login'"
                    class="text-sm text-zinc-400 transition hover:text-white"
                >
                    {{ isAuthed ? 'Open TruLuv' : 'Log in' }}
                </Link>
            </div>
        </header>

        <main class="mx-auto w-full max-w-3xl flex-1 px-4 py-12 sm:px-6">
            <div class="luv-aura -mx-4 mb-10 rounded-3xl px-4 py-10 sm:-mx-6 sm:px-6">
                <h1 class="text-3xl font-bold sm:text-4xl">{{ title }}</h1>
                <p v-if="updated" class="mt-2 text-sm text-zinc-400">
                    Last updated {{ updated }}
                </p>
            </div>

            <article class="space-y-8 leading-relaxed text-zinc-400">
                <slot />
            </article>

            <p class="mt-12 rounded-xl border border-white/10 bg-white/[0.03] p-4 text-sm text-zinc-500">
                This is sample content for a proof of concept and does not
                constitute legal advice.
            </p>
        </main>

        <AppFooter />
    </div>
</template>
