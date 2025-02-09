<?php

namespace App\Repositories\Tenants\Tenants;

use App\Http\Requests\Tenants\StoreRequest;
use App\Http\Requests\Tenants\UpdateRequest;
use App\Models\Tenants\Tenant;
use Illuminate\Database\Eloquent\Collection;

interface TenantsRepository
{
    public function getTenant($id): Tenant;

    public function getAllTenants(): Collection;

    public function createTenant(StoreRequest $request): Tenant;

    public function setDinamicTenantDatabase(Tenant $tenant): void;

    public function destroyDinamicTenantDatabase(): void;

    public function updateTenant(UpdateRequest $request, $id): Tenant;

    public function destroyTenant($id) :void;
}
