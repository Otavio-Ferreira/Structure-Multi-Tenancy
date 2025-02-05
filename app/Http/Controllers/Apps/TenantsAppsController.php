<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\TenantsApps\StoreRequest;
use App\Models\Apps\Apps;
use App\Models\Tenants\TenantApps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantsAppsController extends Controller
{
    public function store(StoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $all_apps = TenantApps::where("tenants_id", $request->tenant_id)->get();
                foreach ($all_apps as $key => $a_app) {
                    $a_app->delete();
                }

                if ($request["apps"]) {
                    foreach ($request["apps"] as $key => $app) {
                        if (Apps::find($app)) {
                            TenantApps::create([
                                "tenants_id" => $request->tenant_id,
                                "apps_id" => $app
                            ]);
                        }
                    }
                }
            });
            return redirect()->back()->with("toast_success", "Apps atualizados com sucesso!");
        } catch (\Throwable $th) {
            return redirect()->back()->with("toast_error", "Erro ao executar tarefas!");
        }
    }
}
