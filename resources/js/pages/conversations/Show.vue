<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Send } from '@lucide/vue';
import BaseAvatar from '@/components/base/BaseAvatar.vue';

const props = defineProps<{
    conversation: {
        id: number;
        other_name: string;
        other_photo: string | null;
    };
    messages: Array<{
        id: number;
        body: string;
        mine: boolean;
        sender_name: string;
        sent_at: string;
    }>;
}>();

const form = useForm({
    body: '',
});

// Render the UTC timestamp in the viewer's own local timezone.
function formatSentAt(iso: string): string {
    return new Date(iso).toLocaleString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
}

function submit(): void {
    form.post(`/conversations/${props.conversation.id}/messages`, {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
}
</script>

<template>
    <Head :title="`Chat with ${conversation.other_name}`" />

    <div class="mx-auto flex h-full w-full max-w-2xl flex-col p-4 sm:p-6">
        <div class="mb-5 flex items-center gap-3">
            <Link
                href="/conversations"
                class="text-zinc-400 transition hover:text-white"
            >
                <ArrowLeft class="h-5 w-5" />
            </Link>
            <BaseAvatar
                :src="conversation.other_photo"
                :alt="conversation.other_name"
                size="sm"
            />
            <h1 class="text-lg font-semibold text-white">
                {{ conversation.other_name }}
            </h1>
        </div>

        <div class="flex-1 space-y-3">
            <p
                v-if="messages.length === 0"
                class="py-10 text-center text-sm text-zinc-500"
            >
                No messages yet. Say hello!
            </p>

            <div
                v-for="message in messages"
                :key="message.id"
                class="flex"
                :class="message.mine ? 'justify-end' : 'justify-start'"
            >
                <div class="max-w-[78%]">
                    <div
                        class="rounded-2xl px-4 py-2.5 text-sm"
                        :class="
                            message.mine
                                ? 'bg-luv-gradient text-white'
                                : 'border border-white/10 bg-white/5 text-zinc-100'
                        "
                    >
                        <p class="whitespace-pre-line">{{ message.body }}</p>
                    </div>
                    <p
                        class="mt-1 text-xs text-zinc-500"
                        :class="message.mine ? 'text-right' : 'text-left'"
                    >
                        {{ formatSentAt(message.sent_at) }}
                    </p>
                </div>
            </div>
        </div>

        <form class="mt-6 flex items-end gap-2" @submit.prevent="submit">
            <div class="flex-1">
                <textarea
                    v-model="form.body"
                    rows="1"
                    placeholder="Write a message..."
                    class="w-full resize-none rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-zinc-500 focus:border-pink-400/50 focus:outline-none focus:ring-2 focus:ring-pink-400/40"
                    @keydown.enter.exact.prevent="submit"
                />
                <p v-if="form.errors.body" class="mt-1 text-sm text-rose-400">
                    {{ form.errors.body }}
                </p>
            </div>
            <button
                type="submit"
                :disabled="form.processing"
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-luv-gradient text-white transition hover:luv-glow disabled:opacity-50"
            >
                <Send class="h-5 w-5" />
            </button>
        </form>
    </div>
</template>
