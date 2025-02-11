<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Tenancy;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class IdentifyTenantByJWT
{
    protected $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        $tenantId = $payload->get('tenant_id') ?? null;

        if ($tenantId) {
            tenancy()->initialize($tenantId);
        } else {
            return response()->json(['error' => 'Tenant não encontrado no token'], 401);
        }

        try {
            $user = $token->authenticate();
            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao autenticar usuário'], 401);
        }

        return $next($request);
    }
}
