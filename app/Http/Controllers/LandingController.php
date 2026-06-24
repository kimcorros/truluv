<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    /**
     * The public TruLuv landing page.
     */
    public function __invoke(Request $request): Response
    {
        $featured = User::query()
            ->whereHas('profile', fn ($query) => $query->whereNotNull('age')->whereNotNull('bio'))
            ->with('profile')
            ->inRandomOrder()
            ->take(8)
            ->get()
            ->map(fn (User $user) => [
                'name' => $user->name,
                'age' => $user->profile->age,
                'photo' => $user->profile->photo_url,
                'bio' => str($user->profile->bio)->limit(60)->value(),
            ]);

        return Inertia::render('Landing', [
            'featured' => $featured,
            'stats' => [
                'members' => 2_400_000,
                'matchesDaily' => 48_000,
                'successStories' => 12_500,
                'matchRate' => 92,
            ],
        ]);
    }
}
