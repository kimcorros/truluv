<?php

namespace App\Models;

use Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * A one-to-one thread between two users. The participants are stored in a
 * canonical order (user_one_id < user_two_id) with a unique constraint, so a
 * given pair can only ever have a single conversation. See ConversationService.
 *
 * @property int $id
 * @property int $user_one_id
 * @property int $user_two_id
 */
#[Fillable(['user_one_id', 'user_two_id'])]
class Conversation extends Model
{
    /** @use HasFactory<ConversationFactory> */
    use HasFactory;

    /**
     * The first participant (lower user id).
     *
     * @return BelongsTo<User, $this>
     */
    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    /**
     * The second participant (higher user id).
     *
     * @return BelongsTo<User, $this>
     */
    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * All messages in the thread, oldest first.
     *
     * @return HasMany<Message, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->oldest();
    }

    /**
     * The most recent message, used for inbox previews.
     *
     * @return HasOne<Message, $this>
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Limit the query to conversations the given user takes part in.
     *
     * @param  Builder<Conversation>  $query
     */
    public function scopeForUser(Builder $query, User $user): void
    {
        // Wrapped so the OR can be safely combined with other constraints.
        $query->where(function (Builder $query) use ($user) {
            $query->where('user_one_id', $user->id)
                ->orWhere('user_two_id', $user->id);
        });
    }

    /**
     * Limit to conversations that have at least one unread message addressed
     * to (i.e. not sent by) the given user.
     *
     * @param  Builder<Conversation>  $query
     */
    public function scopeWithUnreadFor(Builder $query, User $user): void
    {
        $query->whereHas('messages', function (Builder $query) use ($user) {
            $query->where('sender_id', '!=', $user->id)->unread();
        });
    }

    /**
     * Whether the given user is one of the two participants.
     */
    public function hasParticipant(User $user): bool
    {
        return $this->user_one_id === $user->id || $this->user_two_id === $user->id;
    }

    /**
     * The participant who is not the given user.
     */
    public function otherParticipant(User $user): User
    {
        return $this->user_one_id === $user->id
            ? $this->userTwo
            : $this->userOne;
    }
}
