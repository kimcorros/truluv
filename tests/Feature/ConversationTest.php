<?php

use App\Models\Conversation;
use App\Models\User;

test('a user can start a conversation and send a message', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $this->actingAs($alice)
        ->post(route('conversations.store'), ['recipient_id' => $bob->id])
        ->assertRedirect();

    $conversation = Conversation::firstOrFail();

    $this->actingAs($alice)
        ->post(route('messages.store', $conversation), ['body' => 'Hi Bob!'])
        ->assertRedirect(route('conversations.show', $conversation));

    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'sender_id' => $alice->id,
        'body' => 'Hi Bob!',
    ]);
});

test('starting a conversation again reuses the same thread', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $this->actingAs($alice)->post(route('conversations.store'), ['recipient_id' => $bob->id]);
    // The other participant starting it from their side must not duplicate it.
    $this->actingAs($bob)->post(route('conversations.store'), ['recipient_id' => $alice->id]);

    expect(Conversation::count())->toBe(1);
});

test('a user cannot start a conversation with themselves', function () {
    $alice = User::factory()->create();

    $this->actingAs($alice)
        ->post(route('conversations.store'), ['recipient_id' => $alice->id])
        ->assertSessionHasErrors('recipient_id');

    expect(Conversation::count())->toBe(0);
});

test('a user cannot view a conversation they are not part of', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();
    $carol = User::factory()->create();

    $conversation = Conversation::create([
        'user_one_id' => min($alice->id, $bob->id),
        'user_two_id' => max($alice->id, $bob->id),
    ]);

    $this->actingAs($carol)
        ->get(route('conversations.show', $conversation))
        ->assertForbidden();
});

test('a user cannot post messages to a conversation they are not part of', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();
    $carol = User::factory()->create();

    $conversation = Conversation::create([
        'user_one_id' => min($alice->id, $bob->id),
        'user_two_id' => max($alice->id, $bob->id),
    ]);

    $this->actingAs($carol)
        ->post(route('messages.store', $conversation), ['body' => 'Sneaking in'])
        ->assertForbidden();

    $this->assertDatabaseMissing('messages', ['body' => 'Sneaking in']);
});
