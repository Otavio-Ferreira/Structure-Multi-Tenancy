<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\StoreRequest;
use App\Http\Requests\Tenants\UpdateRequest;
use App\Services\Tenants\Tenants\TenantsService;

class TenantsController extends Controller
{
    private $tenantsService;

    public function __construct(
        TenantsService $tenantsService
    )
    {
        $this->tenantsService = $tenantsService;
    }

    public function getTenant($id) {
        return $this->tenantsService->getTenantResponse($id);
    }

    public function getTenants() {
        return $this->tenantsService->getAllTenantsResponse();
    }

    public function setTenant(StoreRequest $request)
    {
        return $this->tenantsService->setTenantsResponse($request);
    }

    public function updateTenant(UpdateRequest $request, $id) {
        return $this->tenantsService->updateTenantsResponse($request, $id);
    }

    public function destroyTenant($id){
        return $this->tenantsService->destroyTenantResponse($id);
    }
}
