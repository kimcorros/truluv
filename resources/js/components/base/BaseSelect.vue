<script setup lang="ts">
import { ChevronDown } from '@lucide/vue';

defineOptions({ inheritAttrs: false });

withDefaults(
    defineProps<{
        label?: string;
        error?: string;
        hint?: string;
        options: Array<{ value: string; label: string }>;
        required?: boolean;
    }>(),
    { required: false },
);

const model = defineModel<string | null>();

let count = 0;
const fieldId = `tl-select-${++count}`;
</script>

<template>
    <div class="space-y-1.5">
        <label
            v-if="label"
            :for="fieldId"
            class="block text-sm font-medium text-zinc-300"
        >
            {{ label }}
            <span v-if="required" class="text-pink-400">*</span>
        </label>
        <div class="relative">
            <select
                :id="fieldId"
                v-bind="$attrs"
                v-model="model"
                class="w-full appearance-none rounded-xl border bg-white/5 px-4 py-2.5 pr-10 text-white transition focus:outline-none focus:ring-2 focus:ring-pink-400/50"
                :class="
                    error
                        ? 'border-rose-500/60'
                        : 'border-white/10 focus:border-pink-400/50'
                "
            >
                <option
                    v-for="opt in options"
                    :key="opt.value"
                    :value="opt.value"
                    class="bg-zinc-900 text-white"
                >
                    {{ opt.label }}
                </option>
            </select>
            <ChevronDown
                class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-zinc-500"
            />
        </div>
        <p v-if="error" class="text-sm text-rose-400">{{ error }}</p>
        <p v-else-if="hint" class="text-xs text-zinc-500">{{ hint }}</p>
    </div>
</template>
