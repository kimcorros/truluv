<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface ConversationSummary {
    id: number;
    other_name: string;
    last_message: string | null;
}

defineProps<{
    conversations: ConversationSummary[];
}>();
</script>

<template>
    <Head title="Inbox" />

    <div class="max-w-2xl p-4">
        <h1 class="mb-4 text-xl font-semibold">Inbox</h1>

        <p v-if="conversations.length === 0" class="text-sm text-gray-600">
            No conversations yet. Find someone on the
            <Link href="/browse" class="underline">browse</Link> page.
        </p>

        <ul class="divide-y divide-gray-200 rounded-lg border border-gray-200">
            <li v-for="conversation in conversations" :key="conversation.id">
                <Link
                    :href="`/conversations/${conversation.id}`"
                    class="block px-4 py-3 hover:bg-gray-50"
                >
                    <span class="font-medium">{{ conversation.other_name }}</span>
                    <span class="ml-2 text-sm text-gray-600">
                        {{ conversation.last_message ?? 'No messages yet' }}
                    </span>
                </Link>
            </li>
        </ul>
    </div>
</template>
