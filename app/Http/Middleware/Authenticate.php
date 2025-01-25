<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // Jika permintaan tidak mengharapkan respon JSON (aplikasi fe, mobile app, dll)
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
