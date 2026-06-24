<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { MessageCircle } from '@lucide/vue';
import BaseAvatar from '@/components/base/BaseAvatar.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseCard from '@/components/base/BaseCard.vue';

interface ConversationSummary {
    id: number;
    other_name: string;
    other_photo: string | null;
    last_message: string | null;
    unread: boolean;
}

defineProps<{
    conversations: ConversationSummary[];
}>();
</script>

<template>
    <Head title="Inbox" />

    <div class="mx-auto w-full max-w-2xl p-4 sm:p-6">
        <h1 class="mb-6 text-2xl font-bold text-white">Inbox</h1>

        <BaseCard
            v-if="conversations.length === 0"
            class="flex flex-col items-center py-14 text-center"
        >
            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-white/5"
            >
                <MessageCircle class="h-6 w-6 text-zinc-400" />
            </div>
            <p class="text-zinc-300">No conversations yet</p>
            <p class="mt-1 text-sm text-zinc-500">
                Find someone you like and break the ice.
            </p>
            <BaseButton href="/browse" class="mt-5">Browse profiles</BaseButton>
        </BaseCard>

        <BaseCard v-else :padded="false" class="divide-y divide-white/5">
            <Link
                v-for="conversation in conversations"
                :key="conversation.id"
                :href="`/conversations/${conversation.id}`"
                class="flex items-center gap-4 p-4 transition hover:bg-white/[0.03]"
            >
                <div class="relative">
                    <BaseAvatar
                        :src="conversation.other_photo"
                        :alt="conversation.other_name"
                        size="md"
                    />
                    <span
                        v-if="conversation.unread"
                        class="absolute -right-0.5 -top-0.5 h-3.5 w-3.5 rounded-full border-2 border-zinc-950 bg-luv-gradient"
                    ></span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-white">
                        {{ conversation.other_name }}
                    </p>
                    <p
                        class="truncate text-sm"
                        :class="
                            conversation.unread
                                ? 'font-medium text-white'
                                : 'text-zinc-400'
                        "
                    >
                        {{ conversation.last_message ?? 'No messages yet' }}
                    </p>
                </div>
                <span
                    v-if="conversation.unread"
                    class="h-2.5 w-2.5 shrink-0 rounded-full bg-pink-500"
                ></span>
            </Link>
        </BaseCard>
    </div>
</template>
