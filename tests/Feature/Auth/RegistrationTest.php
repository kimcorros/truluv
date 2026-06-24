<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    // New users are sent to complete their dating profile.
    $response->assertRedirect(route('profiles.edit', absolute: false));
});

test('a new user can set their gender during registration', function () {
    $this->post(route('register.store'), [
        'name' => 'Robin',
        'email' => 'robin@example.com',
        'gender' => 'non-binary',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'robin@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->profile)->not->toBeNull()
        ->and($user->profile->gender)->toBe('non-binary');
});
