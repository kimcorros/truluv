<script setup lang="ts">
import { watch } from 'vue';
import { useCountUp } from '@/composables/useCountUp';
import { useInView } from '@/composables/useInView';

const props = withDefaults(
    defineProps<{ value: number; suffix?: string }>(),
    { suffix: '' },
);

const { target, isVisible } = useInView({ threshold: 0.4 });
const { value, start } = useCountUp(props.value);

watch(isVisible, (visible) => {
    if (visible) {
        start();
    }
});

const formatter = new Intl.NumberFormat('en-US', {
    notation: 'compact',
    maximumFractionDigits: 1,
});
</script>

<template>
    <div ref="target" class="text-3xl font-bold text-white sm:text-4xl">
        {{ formatter.format(value) }}{{ suffix }}
    </div>
</template>
