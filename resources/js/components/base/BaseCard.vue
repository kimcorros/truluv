<script setup lang="ts">
withDefaults(defineProps<{ hover?: boolean; padded?: boolean }>(), {
    hover: false,
    padded: true,
});
</script>

<template>
    <div
        class="rounded-2xl border border-white/10 bg-white/[0.03] backdrop-blur-sm transition-all duration-300"
        :class="[
            hover
                ? 'hover:-translate-y-1 hover:border-pink-400/30 hover:bg-white/[0.06]'
                : '',
            padded ? 'p-6' : '',
        ]"
    >
        <slot />
    </div>
</template>
