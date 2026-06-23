<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type Variant = 'primary' | 'secondary' | 'outline' | 'ghost';
type Size = 'sm' | 'md' | 'lg';

const props = withDefaults(
    defineProps<{
        variant?: Variant;
        size?: Size;
        href?: string;
        type?: 'button' | 'submit' | 'reset';
        block?: boolean;
        loading?: boolean;
        disabled?: boolean;
    }>(),
    {
        variant: 'primary',
        size: 'md',
        type: 'button',
        block: false,
        loading: false,
        disabled: false,
    },
);

const base =
    'inline-flex items-center justify-center gap-2 rounded-full font-semibold transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-400/60 disabled:opacity-50 disabled:pointer-events-none';

const variants: Record<Variant, string> = {
    primary: 'bg-luv-gradient text-white hover:luv-glow hover:-translate-y-0.5',
    secondary: 'border border-white/10 bg-white/10 text-white hover:bg-white/15',
    outline: 'border border-white/20 text-white hover:bg-white/10',
    ghost: 'text-zinc-300 hover:bg-white/5 hover:text-white',
};

const sizes: Record<Size, string> = {
    sm: 'px-4 py-1.5 text-sm',
    md: 'px-5 py-2.5 text-sm',
    lg: 'px-7 py-3.5 text-base',
};

const classes = computed(() => [
    base,
    variants[props.variant],
    sizes[props.size],
    props.block ? 'w-full' : '',
]);

const isDisabled = computed(() => props.disabled || props.loading);
</script>

<template>
    <Link v-if="href && !isDisabled" :href="href" :class="classes">
        <slot />
    </Link>
    <button v-else :type="type" :class="classes" :disabled="isDisabled">
        <span
            v-if="loading"
            class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"
        />
        <slot />
    </button>
</template>
