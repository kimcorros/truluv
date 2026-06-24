<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    Heart,
    LogOut,
    MessageCircle,
    Search,
    ShieldCheck,
    UserRound,
} from '@lucide/vue';
import { computed, ref } from 'vue';
import BaseAvatar from '@/components/base/BaseAvatar.vue';

// Accepted (and ignored) so kit pages that pass breadcrumbs don't warn.
defineProps<{ breadcrumbs?: unknown[] }>();

const page = usePage();
const auth = computed(
    () =>
        (
            page.props as {
                auth?: {
                    user?: { name?: string; email?: string } | null;
                    photo?: string | null;
                };
            }
        ).auth,
);
const user = computed(() => auth.value?.user ?? null);
const photo = computed(() => auth.value?.photo ?? null);
const currentPath = computed(() => page.url.split('?')[0]);
const unreadCount = computed(() =>
    Number((page.props as { unreadCount?: number }).unreadCount ?? 0),
);

const nav = [
    { label: 'Browse', href: '/browse', icon: Search },
    { label: 'Inbox', href: '/conversations', icon: MessageCircle },
];

const menuOpen = ref(false);

function isActive(href: string): boolean {
    return currentPath.value === href || currentPath.value.startsWith(`${href}/`);
}

function closeMenu(): void {
    menuOpen.value = false;
}

function logout(): void {
    closeMenu();
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

                <div class="flex items-center gap-2">
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
                            <span
                                v-if="
                                    item.href === '/conversations' && unreadCount > 0
                                "
                                class="ml-0.5 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-luv-gradient px-1.5 text-xs font-semibold text-white"
                            >
                                {{ unreadCount }}
                            </span>
                        </Link>
                    </nav>

                    <!-- Avatar dropdown -->
                    <div class="relative ml-1">
                        <button
                            type="button"
                            class="flex rounded-full transition focus:outline-none"
                            :class="
                                menuOpen ? 'ring-2 ring-pink-400/60 ring-offset-2 ring-offset-zinc-950' : ''
                            "
                            @click="menuOpen = !menuOpen"
                        >
                            <BaseAvatar :src="photo" :alt="user?.name ?? '?'" size="sm" />
                        </button>

                        <div
                            v-if="menuOpen"
                            class="fixed inset-0 z-40"
                            @click="closeMenu"
                        ></div>

                        <div
                            v-if="menuOpen"
                            class="absolute right-0 z-50 mt-2 w-56 rounded-2xl border border-white/10 bg-zinc-900 p-1.5 shadow-2xl"
                        >
                            <div class="px-3 py-2">
                                <p class="truncate text-sm font-semibold text-white">
                                    {{ user?.name }}
                                </p>
                                <p class="truncate text-xs text-zinc-400">
                                    {{ user?.email }}
                                </p>
                            </div>
                            <div class="my-1 h-px bg-white/10"></div>
                            <Link
                                href="/profiles/me/edit"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-zinc-300 transition hover:bg-white/5 hover:text-white"
                                @click="closeMenu"
                            >
                                <UserRound class="h-4 w-4" />
                                My profile
                            </Link>
                            <Link
                                href="/settings/security"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-zinc-300 transition hover:bg-white/5 hover:text-white"
                                @click="closeMenu"
                            >
                                <ShieldCheck class="h-4 w-4" />
                                Security
                            </Link>
                            <div class="my-1 h-px bg-white/10"></div>
                            <button
                                type="button"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-300 transition hover:bg-white/5 hover:text-white"
                                @click="logout"
                            >
                                <LogOut class="h-4 w-4" />
                                Log out
                            </button>
                        </div>
                    </div>
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
                    <span
                        v-if="item.href === '/conversations' && unreadCount > 0"
                        class="ml-0.5 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-luv-gradient px-1.5 text-xs font-semibold text-white"
                    >
                        {{ unreadCount }}
                    </span>
                </Link>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl">
            <slot />
        </main>
    </div>
</template>
