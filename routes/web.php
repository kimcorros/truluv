<?php

use App\Http\Controllers\BrowseController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Send visitors straight into the app: browse if logged in, otherwise login.
Route::get('/', fn () => redirect()->route(auth()->check() ? 'browse' : 'login'))->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Current user's own dating profile. Declared before profiles/{user}
    // so the "me" segment is never mistaken for a user id.
    Route::get('profiles/me/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::put('profiles/me', [ProfileController::class, 'update'])->name('profiles.update');

    // Browse and view other users.
    Route::get('browse', [BrowseController::class, 'index'])->name('browse');
    Route::get('profiles/{user}', [ProfileController::class, 'show'])
        ->whereNumber('user')
        ->name('profiles.show');

    // Conversations and messages.
    Route::get('conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::post('conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
});

require __DIR__.'/settings.php';
