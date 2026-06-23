<script setup lang="ts">
import { computed } from 'vue';
import { useInView } from '@/composables/useInView';

const props = withDefaults(
    defineProps<{
        /** Element to render as the reveal wrapper. */
        as?: string;
        /** Stagger the reveal by N milliseconds. */
        delay?: number;
    }>(),
    { as: 'div', delay: 0 },
);

const { target, isVisible } = useInView();

const style = computed(() =>
    props.delay ? { transitionDelay: `${props.delay}ms` } : undefined,
);
</script>

<template>
    <component
        :is="as"
        ref="target"
        class="reveal"
        :class="{ 'is-visible': isVisible }"
        :style="style"
    >
        <slot />
    </component>
</template>
