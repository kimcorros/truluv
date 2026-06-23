<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    conversation: {
        id: number;
        other_name: string;
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

function submit(): void {
    form.post(`/conversations/${props.conversation.id}/messages`, {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
}
</script>

<template>
    <Head :title="`Chat with ${conversation.other_name}`" />

    <div class="max-w-2xl p-4">
        <Link href="/conversations" class="text-sm underline">&larr; Back to inbox</Link>

        <h1 class="mt-4 mb-4 text-xl font-semibold">{{ conversation.other_name }}</h1>

        <div class="space-y-3">
            <p v-if="messages.length === 0" class="text-sm text-gray-600">
                No messages yet. Say hello!
            </p>

            <div
                v-for="message in messages"
                :key="message.id"
                class="rounded-lg border border-gray-200 p-3"
                :class="message.mine ? 'bg-gray-100' : 'bg-white'"
            >
                <div class="text-xs text-gray-500">
                    {{ message.sender_name }} &middot; {{ message.sent_at }}
                </div>
                <div class="mt-1 whitespace-pre-line">{{ message.body }}</div>
            </div>
        </div>

        <form class="mt-6 space-y-2" @submit.prevent="submit">
            <textarea
                v-model="form.body"
                rows="3"
                placeholder="Write a message..."
                class="w-full rounded border border-gray-300 px-3 py-2"
            ></textarea>
            <p v-if="form.errors.body" class="text-sm text-red-600">{{ form.errors.body }}</p>
            <button
                type="submit"
                :disabled="form.processing"
                class="rounded bg-gray-900 px-4 py-2 text-sm text-white disabled:opacity-50"
            >
                Send
            </button>
        </form>
    </div>
</template>
