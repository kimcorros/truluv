<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    Heart,
    Lock,
    MessageCircle,
    Quote,
    Search,
    ShieldCheck,
    Sparkles,
    Star,
} from '@lucide/vue';
import { computed } from 'vue';
import AppFooter from '@/components/AppFooter.vue';
import AppContainer from '@/components/base/AppContainer.vue';
import BaseAvatar from '@/components/base/BaseAvatar.vue';
import BaseBadge from '@/components/base/BaseBadge.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseCard from '@/components/base/BaseCard.vue';
import Reveal from '@/components/base/Reveal.vue';
import StatCounter from '@/components/base/StatCounter.vue';

interface FeaturedUser {
    name: string;
    age: number;
    photo: string | null;
    bio: string;
}

const props = defineProps<{
    featured: FeaturedUser[];
    stats: {
        members: number;
        matchesDaily: number;
        successStories: number;
        matchRate: number;
    };
}>();

const page = usePage();
const isAuthed = computed(
    () => !!(page.props as { auth?: { user?: unknown } }).auth?.user,
);
const appHref = computed(() => (isAuthed.value ? '/browse' : '/register'));

const steps = [
    {
        icon: Sparkles,
        title: 'Create your profile',
        text: 'Tell your story in a few lines. A photo, your age, and what makes you, you.',
    },
    {
        icon: Search,
        title: 'Discover people',
        text: 'Browse real members nearby and find the ones worth your time.',
    },
    {
        icon: MessageCircle,
        title: 'Start the conversation',
        text: 'Like what you see? Say hello and let the chemistry take over.',
    },
];

const testimonials = [
    {
        quote: 'We matched on a Tuesday and never stopped talking. Best swipe of my life.',
        names: 'Maya & Daniel',
        meta: 'Matched 2024',
        a: 'https://randomuser.me/api/portraits/women/44.jpg',
        b: 'https://randomuser.me/api/portraits/men/32.jpg',
    },
    {
        quote: 'TruLuv felt different — real people, real intentions. We move in next month.',
        names: 'Priya & Sam',
        meta: 'Together 1 year',
        a: 'https://randomuser.me/api/portraits/women/68.jpg',
        b: 'https://randomuser.me/api/portraits/men/75.jpg',
    },
    {
        quote: 'I almost gave up on apps. One message here changed everything.',
        names: 'Elena & Marco',
        meta: 'Engaged 2025',
        a: 'https://randomuser.me/api/portraits/women/90.jpg',
        b: 'https://randomuser.me/api/portraits/men/15.jpg',
    },
];

const safety = [
    {
        icon: ShieldCheck,
        title: 'Verified members',
        text: 'Real faces, real people. We keep the bots and fakes out.',
    },
    {
        icon: Lock,
        title: 'Privacy first',
        text: 'You control who sees you. Your data is never sold.',
    },
    {
        icon: Heart,
        title: 'Built for connection',
        text: 'No endless swiping for its own sake — just meaningful matches.',
    },
];
</script>

<template>
    <Head title="Find your kind of love" />

    <div class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
        <!-- Nav -->
        <header
            class="sticky top-0 z-50 border-b border-white/5 bg-zinc-950/70 backdrop-blur-xl"
        >
            <AppContainer>
                <div class="flex h-16 items-center justify-between">
                    <Link href="/" class="flex items-center gap-2">
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-luv-gradient"
                        >
                            <Heart class="h-4 w-4 text-white" fill="currentColor" />
                        </span>
                        <span class="text-lg font-bold tracking-tight">
                            Tru<span class="text-gradient">Luv</span>
                        </span>
                    </Link>

                    <nav class="hidden items-center gap-8 text-sm text-zinc-400 md:flex">
                        <a href="#how" class="transition hover:text-white">How it works</a>
                        <a href="#singles" class="transition hover:text-white">Singles</a>
                        <a href="#stories" class="transition hover:text-white">Stories</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        <template v-if="isAuthed">
                            <BaseButton href="/browse" size="sm">Open TruLuv</BaseButton>
                        </template>
                        <template v-else>
                            <BaseButton href="/login" variant="ghost" size="sm">
                                Log in
                            </BaseButton>
                            <BaseButton href="/register" size="sm">Join free</BaseButton>
                        </template>
                    </div>
                </div>
            </AppContainer>
        </header>

        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 luv-aura"></div>
            <div
                class="pointer-events-none absolute -top-48 left-1/2 h-[42rem] w-[42rem] -translate-x-1/2 rounded-full bg-pink-500/10 blur-3xl"
            ></div>

            <AppContainer>
                <div
                    class="relative grid items-center gap-12 py-20 lg:grid-cols-2 lg:py-28"
                >
                    <div>
                        <BaseBadge variant="outline" class="mb-6">
                            <Sparkles class="h-3.5 w-3.5 text-pink-400" />
                            Where real connections begin
                        </BaseBadge>

                        <h1
                            class="text-4xl font-bold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl"
                        >
                            Find your kind of
                            <span class="text-gradient">love.</span>
                        </h1>

                        <p class="mt-6 max-w-md text-lg text-zinc-400">
                            TruLuv is where genuine people meet. No games, no noise —
                            just real connections waiting to happen.
                        </p>

                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <BaseButton :href="appHref" size="lg">
                                {{ isAuthed ? 'Open TruLuv' : 'Join free' }}
                                <ArrowRight class="h-4 w-4" />
                            </BaseButton>
                            <BaseButton
                                v-if="!isAuthed"
                                href="/login"
                                variant="outline"
                                size="lg"
                            >
                                I have an account
                            </BaseButton>
                        </div>

                        <div class="mt-10 flex items-center gap-4">
                            <div class="flex -space-x-3">
                                <BaseAvatar
                                    v-for="user in props.featured.slice(0, 4)"
                                    :key="user.name"
                                    :src="user.photo"
                                    :alt="user.name"
                                    size="sm"
                                    class="ring-2 ring-zinc-950"
                                />
                            </div>
                            <p class="text-sm text-zinc-400">
                                Join
                                <span class="font-semibold text-white">2.4M+</span>
                                singles finding love
                            </p>
                        </div>
                    </div>

                    <!-- Floating member cards -->
                    <div class="relative hidden h-[30rem] lg:block">
                        <div
                            v-if="props.featured[0]"
                            class="animate-float absolute left-6 top-4 w-52"
                        >
                            <BaseCard padded class="luv-glow">
                                <img
                                    :src="props.featured[0].photo ?? undefined"
                                    :alt="props.featured[0].name"
                                    class="mb-3 aspect-square w-full rounded-xl object-cover"
                                />
                                <p class="font-semibold text-white">
                                    {{ props.featured[0].name }}, {{ props.featured[0].age }}
                                </p>
                                <p class="text-xs text-pink-400">Online now</p>
                            </BaseCard>
                        </div>

                        <div
                            v-if="props.featured[1]"
                            class="animate-float-slow absolute right-2 top-24 w-48"
                        >
                            <BaseCard padded>
                                <img
                                    :src="props.featured[1].photo ?? undefined"
                                    :alt="props.featured[1].name"
                                    class="mb-3 aspect-square w-full rounded-xl object-cover"
                                />
                                <p class="font-semibold text-white">
                                    {{ props.featured[1].name }}, {{ props.featured[1].age }}
                                </p>
                                <p class="text-xs text-zinc-400">2 km away</p>
                            </BaseCard>
                        </div>

                        <div
                            v-if="props.featured[2]"
                            class="animate-float absolute bottom-2 left-16 w-44"
                            style="animation-delay: 1.5s"
                        >
                            <BaseCard padded>
                                <img
                                    :src="props.featured[2].photo ?? undefined"
                                    :alt="props.featured[2].name"
                                    class="mb-3 aspect-square w-full rounded-xl object-cover"
                                />
                                <p class="font-semibold text-white">
                                    {{ props.featured[2].name }}, {{ props.featured[2].age }}
                                </p>
                                <p class="text-xs text-zinc-400">New here</p>
                            </BaseCard>
                        </div>
                    </div>
                </div>
            </AppContainer>
        </section>

        <!-- Stats -->
        <section class="border-y border-white/5 bg-white/[0.02]">
            <AppContainer>
                <div class="grid grid-cols-2 gap-8 py-12 lg:grid-cols-4">
                    <Reveal class="text-center">
                        <StatCounter :value="props.stats.members" />
                        <p class="mt-1 text-sm text-zinc-400">Members</p>
                    </Reveal>
                    <Reveal class="text-center" :delay="100">
                        <StatCounter :value="props.stats.matchesDaily" />
                        <p class="mt-1 text-sm text-zinc-400">Matches / day</p>
                    </Reveal>
                    <Reveal class="text-center" :delay="200">
                        <StatCounter :value="props.stats.successStories" />
                        <p class="mt-1 text-sm text-zinc-400">Success stories</p>
                    </Reveal>
                    <Reveal class="text-center" :delay="300">
                        <StatCounter :value="props.stats.matchRate" suffix="%" />
                        <p class="mt-1 text-sm text-zinc-400">Match rate</p>
                    </Reveal>
                </div>
            </AppContainer>
        </section>

        <!-- How it works -->
        <section id="how" class="py-24">
            <AppContainer>
                <Reveal class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold sm:text-4xl">
                        Love, in three easy steps
                    </h2>
                    <p class="mt-4 text-zinc-400">
                        Getting started takes a minute. Finding your person takes a
                        spark.
                    </p>
                </Reveal>

                <div class="mt-14 grid gap-6 md:grid-cols-3">
                    <Reveal
                        v-for="(step, i) in steps"
                        :key="step.title"
                        :delay="i * 120"
                    >
                        <BaseCard hover class="h-full">
                            <div
                                class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-luv-gradient"
                            >
                                <component :is="step.icon" class="h-6 w-6 text-white" />
                            </div>
                            <h3 class="text-lg font-semibold text-white">
                                {{ step.title }}
                            </h3>
                            <p class="mt-2 text-sm text-zinc-400">{{ step.text }}</p>
                        </BaseCard>
                    </Reveal>
                </div>
            </AppContainer>
        </section>

        <!-- Featured singles -->
        <section id="singles" class="py-12">
            <AppContainer>
                <Reveal class="mx-auto max-w-2xl text-center">
                    <BaseBadge variant="soft" class="mb-4">Featured singles</BaseBadge>
                    <h2 class="text-3xl font-bold sm:text-4xl">
                        Meet real people, ready for real connections
                    </h2>
                </Reveal>

                <div
                    class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4"
                >
                    <Reveal
                        v-for="(user, i) in props.featured"
                        :key="user.name + i"
                        :delay="(i % 4) * 80"
                    >
                        <Link :href="appHref" class="group block">
                            <div
                                class="relative overflow-hidden rounded-2xl border border-white/10"
                            >
                                <img
                                    :src="user.photo ?? undefined"
                                    :alt="user.name"
                                    class="aspect-[3/4] w-full object-cover transition duration-500 group-hover:scale-105"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent"
                                ></div>
                                <div class="absolute inset-x-0 bottom-0 p-4">
                                    <p class="font-semibold text-white">
                                        {{ user.name }}, {{ user.age }}
                                    </p>
                                    <p class="line-clamp-1 text-xs text-zinc-300">
                                        {{ user.bio }}
                                    </p>
                                </div>
                                <div
                                    class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-zinc-950/50 backdrop-blur transition group-hover:bg-luv-gradient"
                                >
                                    <Heart class="h-4 w-4 text-white" />
                                </div>
                            </div>
                        </Link>
                    </Reveal>
                </div>
            </AppContainer>
        </section>

        <!-- Testimonials -->
        <section id="stories" class="py-24">
            <AppContainer>
                <Reveal class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold sm:text-4xl">
                        Real stories, real love
                    </h2>
                    <p class="mt-4 text-zinc-400">
                        Thousands of couples started right here.
                    </p>
                </Reveal>

                <div class="mt-14 grid gap-6 md:grid-cols-3">
                    <Reveal
                        v-for="(t, i) in testimonials"
                        :key="t.names"
                        :delay="i * 120"
                    >
                        <BaseCard class="flex h-full flex-col">
                            <Quote class="h-7 w-7 text-pink-400/70" />
                            <p class="mt-4 flex-1 text-zinc-200">"{{ t.quote }}"</p>
                            <div class="mt-6 flex items-center gap-3">
                                <div class="flex -space-x-3">
                                    <BaseAvatar :src="t.a" size="sm" class="ring-2 ring-zinc-950" />
                                    <BaseAvatar :src="t.b" size="sm" class="ring-2 ring-zinc-950" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">
                                        {{ t.names }}
                                    </p>
                                    <p class="flex items-center gap-1 text-xs text-zinc-400">
                                        <Star class="h-3 w-3 text-pink-400" fill="currentColor" />
                                        {{ t.meta }}
                                    </p>
                                </div>
                            </div>
                        </BaseCard>
                    </Reveal>
                </div>
            </AppContainer>
        </section>

        <!-- Safety -->
        <section class="border-y border-white/5 bg-white/[0.02] py-20">
            <AppContainer>
                <div class="grid gap-10 md:grid-cols-3">
                    <Reveal
                        v-for="(item, i) in safety"
                        :key="item.title"
                        :delay="i * 100"
                        class="flex gap-4"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5"
                        >
                            <component :is="item.icon" class="h-5 w-5 text-pink-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">{{ item.title }}</h3>
                            <p class="mt-1 text-sm text-zinc-400">{{ item.text }}</p>
                        </div>
                    </Reveal>
                </div>
            </AppContainer>
        </section>

        <!-- Final CTA -->
        <section class="py-24">
            <AppContainer>
                <Reveal>
                    <div
                        class="relative overflow-hidden rounded-3xl border border-white/10 bg-luv-gradient px-8 py-16 text-center"
                    >
                        <div class="pointer-events-none absolute inset-0 bg-zinc-950/10"></div>
                        <div class="relative">
                            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                                Your person is out there.
                            </h2>
                            <p class="mx-auto mt-4 max-w-md text-white/80">
                                Make the first move today. It's free to join, and love
                                doesn't wait.
                            </p>
                            <div class="mt-8 flex justify-center">
                                <BaseButton
                                    :href="appHref"
                                    variant="secondary"
                                    size="lg"
                                    class="!bg-white !text-zinc-900 hover:!bg-white/90"
                                >
                                    {{ isAuthed ? 'Open TruLuv' : 'Create your free account' }}
                                    <ArrowRight class="h-4 w-4" />
                                </BaseButton>
                            </div>
                        </div>
                    </div>
                </Reveal>
            </AppContainer>
        </section>

        <!-- Footer -->
        <AppFooter />
    </div>
</template>
