<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { Heart, LogOut, MessageCircle, Search, UserRound } from '@lucide/vue';
import { computed } from 'vue';
import BaseAvatar from '@/components/base/BaseAvatar.vue';

// Accepted (and ignored) so kit pages that pass breadcrumbs don't warn.
defineProps<{ breadcrumbs?: unknown[] }>();

const page = usePage();
const user = computed(
    () =>
        (page.props as { auth?: { user?: { name?: string } } }).auth?.user ??
        null,
);
const currentPath = computed(() => page.url.split('?')[0]);

const nav = [
    { label: 'Browse', href: '/browse', icon: Search },
    { label: 'Inbox', href: '/conversations', icon: MessageCircle },
    { label: 'My profile', href: '/profiles/me/edit', icon: UserRound },
];

function isActive(href: string): boolean {
    return currentPath.value === href || currentPath.value.startsWith(`${href}/`);
}

function logout(): void {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-svh bg-zinc-950 text-zinc-100">
        <header
            class="sticky top-0 z-50 border-b border-white/5 bg-zinc-950/70 backdrop-blur-xl"
        >
            <div
                class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8"
            >
                <div class="flex items-center gap-8">
                    <Link href="/browse" class="flex items-center gap-2">
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-luv-gradient"
                        >
                            <Heart class="h-4 w-4 text-white" fill="currentColor" />
                        </span>
                        <span class="text-lg font-bold">
                            Tru<span class="text-gradient">Luv</span>
                        </span>
                    </Link>

                    <nav class="hidden items-center gap-1 sm:flex">
                        <Link
                            v-for="item in nav"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center gap-2 rounded-full px-4 py-2 text-sm transition"
                            :class="
                                isActive(item.href)
                                    ? 'bg-white/10 text-white'
                                    : 'text-zinc-400 hover:bg-white/5 hover:text-white'
                            "
                        >
                            <component :is="item.icon" class="h-4 w-4" />
                            {{ item.label }}
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center gap-2">
                    <Link href="/settings/profile" class="hidden sm:block">
                        <BaseAvatar :alt="user?.name ?? '?'" size="sm" />
                    </Link>
                    <button
                        type="button"
                        class="flex items-center gap-1.5 rounded-full px-3 py-2 text-sm text-zinc-400 transition hover:bg-white/5 hover:text-white"
                        @click="logout"
                    >
                        <LogOut class="h-4 w-4" />
                        <span class="hidden sm:inline">Log out</span>
                    </button>
                </div>
            </div>

            <!-- Mobile nav -->
            <nav
                class="flex items-center gap-1 overflow-x-auto border-t border-white/5 px-4 py-2 sm:hidden"
            >
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    class="flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1.5 text-sm transition"
                    :class="
                        isActive(item.href)
                            ? 'bg-white/10 text-white'
                            : 'text-zinc-400'
                    "
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.label }}
                </Link>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl">
            <slot />
        </main>
    </div>
</template>
