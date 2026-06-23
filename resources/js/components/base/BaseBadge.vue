<script setup lang="ts">
import { computed } from 'vue';

type Variant = 'gradient' | 'soft' | 'outline';

const props = withDefaults(defineProps<{ variant?: Variant }>(), {
    variant: 'soft',
});

const variants: Record<Variant, string> = {
    gradient: 'bg-luv-gradient text-white',
    soft: 'bg-white/10 text-zinc-200',
    outline: 'border border-white/20 text-zinc-300',
};

const classes = computed(() => variants[props.variant]);
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium"
        :class="classes"
    >
        <slot />
    </span>
</template>
