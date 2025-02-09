<?php

namespace App\Repositories\Tenants\Tenants;

use App\Http\Requests\Tenants\StoreRequest;
use App\Http\Requests\Tenants\UpdateRequest;
use App\Models\Tenants\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EloquentTenantsRepository implements TenantsRepository
{

    public function getTenant($id): Tenant
    {
        return Tenant::find($id);
    }

    public function getAllTenants(): Collection
    {
        return Tenant::all();
    }

    public function createTenant(StoreRequest $request): Tenant
    {
        $tenant = Tenant::create([
            "name" => $request->name,
            "tenancy_db_name" => $request->tenancy_db_name,
            "tenancy_db_host" => $request->tenancy_db_host,
            "tenancy_db_user" => $request->tenancy_db_user,
            "tenancy_db_password" => $request->tenancy_db_password,
            "tenancy_db_port" => $request->tenancy_db_port,
        ]);

        return $tenant;
    }
    public function setDinamicTenantDatabase(Tenant $tenant): void
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $tenant->tenancy_db_host,
            'port' => $tenant->tenancy_db_port,
            'database' => $tenant->tenancy_db_name,
            'username' => $tenant->tenancy_db_user,
            'password' => $tenant->tenancy_db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        Config::set('database.default', 'tenant');
        DB::purge();
        DB::reconnect();
    }

    public function destroyDinamicTenantDatabase(): void
    {
        Config::set('database.default', 'mysql');
        DB::purge();
        DB::reconnect();
    }

    public function updateTenant(UpdateRequest $request, $id): Tenant
    {
        $tenant = $this->getTenant($id);
        $tenant->name = $request->name;
        $tenant->tenancy_db_name = $request->tenancy_db_name;
        $tenant->tenancy_db_host = $request->tenancy_db_host;
        $tenant->tenancy_db_user = $request->tenancy_db_user;
        $tenant->tenancy_db_password = $request->tenancy_db_password;
        $tenant->tenancy_db_port = $request->tenancy_db_port;
        $tenant->save();

        $tenant->domains()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            ['domain' => $request->domain]
        );

        return $tenant;
    }

    public function destroyTenant($id): void
    {
        $tenant = $this->getTenant($id);
        $tenant->delete();
    }
}
