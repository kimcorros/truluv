<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Send a freshly registered user to complete their dating profile.
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->intended(route('profiles.edit'));
    }
}
