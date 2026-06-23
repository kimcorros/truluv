<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\User;

class ConversationService
{
    /**
     * Find the existing conversation between two users, or create it.
     *
     * Participants are stored in a canonical order (lower id as user_one_id)
     * so that the unique constraint on (user_one_id, user_two_id) guarantees a
     * single thread per pair regardless of who starts it.
     */
    public function findOrCreateBetween(User $a, User $b): Conversation
    {
        return Conversation::firstOrCreate([
            'user_one_id' => min($a->id, $b->id),
            'user_two_id' => max($a->id, $b->id),
        ]);
    }
}
