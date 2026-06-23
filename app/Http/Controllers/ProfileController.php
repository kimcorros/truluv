<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the form to edit the current user's dating profile.
     */
    public function edit(Request $request): Response
    {
        $profile = $request->user()->profile;

        return Inertia::render('profiles/Edit', [
            'profile' => [
                'age' => $profile?->age,
                'gender' => $profile?->gender,
                'bio' => $profile?->bio,
            ],
        ]);
    }

    /**
     * Create or update the current user's dating profile.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->profile()->updateOrCreate(
            [],
            $request->validated(),
        );

        return to_route('browse');
    }

    /**
     * Show another user's full profile.
     */
    public function show(User $user): Response
    {
        $user->load('profile');

        abort_if($user->profile === null, 404);

        return Inertia::render('profiles/Show', [
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'age' => $user->profile->age,
                'gender' => $user->profile->gender,
                'bio' => $user->profile->bio,
            ],
        ]);
    }
}
