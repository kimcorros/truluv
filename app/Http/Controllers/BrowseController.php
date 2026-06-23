<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BrowseController extends Controller
{
    /**
     * Browse other users' profiles in a paginated card view.
     */
    public function index(Request $request): Response
    {
        $users = User::query()
            ->whereKeyNot($request->user()->id)
            ->whereHas('profile')
            ->with('profile')
            ->orderBy('name')
            ->paginate(9)
            ->through(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'age' => $user->profile->age,
                'photo' => $user->profile->photo_url,
                'bio_excerpt' => Str::limit($user->profile->bio, 120),
            ]);

        return Inertia::render('Browse', [
            'users' => $users,
        ]);
    }
}
