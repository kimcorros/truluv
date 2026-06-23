<script setup lang="ts">
import { computed } from 'vue';

type Size = 'sm' | 'md' | 'lg' | 'xl';

const props = withDefaults(
    defineProps<{
        src?: string | null;
        alt?: string;
        size?: Size;
        ring?: boolean;
    }>(),
    { size: 'md', ring: false, alt: '' },
);

const sizes: Record<Size, string> = {
    sm: 'h-10 w-10',
    md: 'h-14 w-14',
    lg: 'h-20 w-20',
    xl: 'h-28 w-28',
};

const initials = computed(() =>
    (props.alt || '?')
        .split(' ')
        .map((part) => part.charAt(0))
        .slice(0, 2)
        .join('')
        .toUpperCase(),
);
</script>

<template>
    <div
        class="relative inline-flex shrink-0 overflow-hidden rounded-full bg-white/10"
        :class="[
            sizes[size],
            ring ? 'ring-2 ring-pink-400/60 ring-offset-2 ring-offset-zinc-950' : '',
        ]"
    >
        <img
            v-if="src"
            :src="src"
            :alt="alt"
            class="h-full w-full object-cover"
            loading="lazy"
        />
        <span
            v-else
            class="flex h-full w-full items-center justify-center text-sm font-semibold text-zinc-300"
        >
            {{ initials }}
        </span>
    </div>
</template>
