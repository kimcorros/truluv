<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Heart } from '@lucide/vue';

const year = new Date().getFullYear();
</script>

<template>
    <footer class="border-t border-white/5 py-8">
        <div
            class="mx-auto flex max-w-6xl flex-col items-center gap-4 px-4 sm:flex-row sm:justify-between sm:px-6 lg:px-8"
        >
            <Link href="/" class="flex items-center gap-2">
                <span
                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-luv-gradient"
                >
                    <Heart class="h-3.5 w-3.5 text-white" fill="currentColor" />
                </span>
                <span class="font-bold">
                    Tru<span class="text-gradient">Luv</span>
                </span>
            </Link>

            <nav class="flex items-center gap-6 text-sm text-zinc-400">
                <Link href="/terms" class="transition hover:text-white">
                    Terms &amp; Conditions
                </Link>
                <Link href="/privacy" class="transition hover:text-white">
                    Privacy Policy
                </Link>
            </nav>

            <p class="text-sm text-zinc-500">
                &copy; {{ year }} TruLuv. Made with love.
            </p>
        </div>
    </footer>
</template>
