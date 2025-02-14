<?php

namespace App\Http\Controllers\Api\Tenants\Apps;

use App\Http\Controllers\Controller;
use App\Models\Apps\Apps;
use App\Models\Tenants\TenantApps;
use App\Models\Tenants\TenantUsersApps;
use App\Services\Apps\AppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppsController extends Controller
{
    protected $appService;

    public function __construct(
        AppService $appService
    ) {
        $this->appService = $appService;
    }

    public function getApp($id) {}

    public function getApps()
    {
        return $this->appService->getAllTennatsAppsResponse();
    }

    public function setAppToUser(Request $request)
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
            $tenantId = $payload->get('tenant_id') ?? null;

            if (!$tenantId) {
                return response()->json(['error' => 'Tenant ID não encontrado no token'], 400);
            }

            $tenant_app = TenantApps::where(['tenants_id' => $tenantId, 'apps_id' => $request->app_id])->first();
            TenantUsersApps::create([
                "tenant_apps_id" => $tenant_app->id,
                "tenant_user_id" => $request->user_id
            ]);

            return response()->json([
                "success" => true,
                "message" => "App inserido para o usuário com sucesso.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Erro no servidor.",
                "errors" => $th->getMessage()
            ], 500);
        }
    }
}
