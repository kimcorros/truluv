<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Participants are stored in canonical order (lower id first) to match
     * the unique constraint enforced by ConversationService.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userOne = User::factory();
        $userTwo = User::factory();

        return [
            'user_one_id' => $userOne,
            'user_two_id' => $userTwo,
        ];
    }

    /**
     * Create the conversation between two specific users, ordered canonically.
     */
    public function between(User $a, User $b): static
    {
        return $this->state(fn () => [
            'user_one_id' => min($a->id, $b->id),
            'user_two_id' => max($a->id, $b->id),
        ]);
    }
}
