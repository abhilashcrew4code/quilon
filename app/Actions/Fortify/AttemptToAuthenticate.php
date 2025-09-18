<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Fortify;

class AttemptToAuthenticate
{
    public function __invoke($request)
    {
        return Fortify::authenticateThrough(function () use ($request) {
            return array_merge($request->only(Fortify::username(), 'password'), [
                'status' => 1, // You can add extra conditions if needed
            ]);
        });
    }
}
