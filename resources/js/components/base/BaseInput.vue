<script setup lang="ts">
defineOptions({ inheritAttrs: false });

withDefaults(
    defineProps<{
        label?: string;
        type?: string;
        error?: string;
        hint?: string;
        placeholder?: string;
        required?: boolean;
    }>(),
    { type: 'text', required: false },
);

const model = defineModel<string | number | null>();

// Module-local counter gives each field a stable, unique id for the label.
let count = 0;
const inputId = `tl-input-${++count}`;
</script>

<template>
    <div class="space-y-1.5">
        <label
            v-if="label"
            :for="inputId"
            class="block text-sm font-medium text-zinc-300"
        >
            {{ label }}
            <span v-if="required" class="text-pink-400">*</span>
        </label>
        <input
            :id="inputId"
            v-bind="$attrs"
            v-model="model"
            :type="type"
            :placeholder="placeholder"
            class="w-full rounded-xl border bg-white/5 px-4 py-2.5 text-white transition placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-pink-400/50"
            :class="
                error
                    ? 'border-rose-500/60'
                    : 'border-white/10 focus:border-pink-400/50'
            "
        />
        <p v-if="error" class="text-sm text-rose-400">{{ error }}</p>
        <p v-else-if="hint" class="text-xs text-zinc-500">{{ hint }}</p>
    </div>
</template>
