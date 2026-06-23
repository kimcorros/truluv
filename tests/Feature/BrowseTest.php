<?php

use App\Models\Profile;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('browse lists other users but never the current user', function () {
    $me = User::factory()->has(Profile::factory())->create();
    User::factory()->count(2)->has(Profile::factory())->create();

    $this->actingAs($me)
        ->get(route('browse'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Browse')
            ->has('users.data', 2)
        );
});

test('users without a profile are not shown', function () {
    $me = User::factory()->has(Profile::factory())->create();
    User::factory()->has(Profile::factory())->create();
    User::factory()->create(); // no profile yet

    $this->actingAs($me)
        ->get(route('browse'))
        ->assertInertia(fn (AssertableInertia $page) => $page->has('users.data', 1));
});
