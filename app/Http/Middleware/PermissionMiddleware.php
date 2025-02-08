<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle($request, Closure $next, ...$permissions)
    {
        if (Auth::check()) {
            foreach ($permissions as $permission) {
                if (!Auth::user()->can($permission)) {
                    return response()->json([
                        'validate' => false,
                        'message' => 'Usuário não possui permissão para essa ação.',
                    ], 403);
                }
            }
    
            return $next($request);
        }
    
        return response()->json([
            'validate' => false,
            'message' => 'Usuário não autenticado.',
        ], 401);

        return $next($request);
    }
}
