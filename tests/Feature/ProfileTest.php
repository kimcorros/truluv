<?php

use App\Models\User;

test('the profile editor can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profiles.edit'))
        ->assertOk();
});

test('a user can create their dating profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('profiles.update'), [
            'age' => 30,
            'gender' => 'female',
            'bio' => 'Hello, nice to meet you.',
        ])
        ->assertRedirect(route('browse'));

    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'age' => 30,
        'bio' => 'Hello, nice to meet you.',
    ]);
});

test('saving the profile again updates the same row', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('profiles.update'), ['age' => 30, 'bio' => 'First']);
    $this->actingAs($user)->put(route('profiles.update'), ['age' => 31, 'bio' => 'Second']);

    expect($user->profile()->count())->toBe(1);
    $this->assertDatabaseHas('profiles', ['user_id' => $user->id, 'age' => 31]);
});

test('age must be within a sane range', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('profiles.update'), ['age' => 10, 'bio' => 'Too young'])
        ->assertSessionHasErrors('age');

    $this->actingAs($user)
        ->put(route('profiles.update'), ['age' => 200, 'bio' => 'Too old'])
        ->assertSessionHasErrors('age');
});
