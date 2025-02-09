<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->header('Authorization')) {
            return response()->json([
                'message' => 'Token de autenticação ausente.',
            ], 401);
        }

        if (!$request->user()) {
            return response()->json([
                'message' => 'Token inválido ou expirado.',
            ], 401);
        }

        return null;
    }

}
