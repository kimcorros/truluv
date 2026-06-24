<script setup lang="ts">
import { Mars, Venus, VenusAndMars } from '@lucide/vue';
import { computed, type Component } from 'vue';

const props = defineProps<{
    gender?: string | null;
    showLabel?: boolean;
}>();

interface GenderMeta {
    icon: Component;
    label: string;
    class: string;
}

const map: Record<string, GenderMeta> = {
    female: { icon: Venus, label: 'Woman', class: 'text-pink-400' },
    male: { icon: Mars, label: 'Man', class: 'text-sky-400' },
    'non-binary': {
        icon: VenusAndMars,
        label: 'Non-binary',
        class: 'text-purple-400',
    },
};

const entry = computed<GenderMeta | undefined>(() =>
    props.gender ? map[props.gender] : undefined,
);
</script>

<template>
    <span
        v-if="entry"
        class="inline-flex items-center gap-1"
        :class="entry.class"
        :title="entry.label"
    >
        <component :is="entry.icon" class="h-4 w-4" />
        <span v-if="showLabel" class="text-xs">{{ entry.label }}</span>
    </span>
</template>
