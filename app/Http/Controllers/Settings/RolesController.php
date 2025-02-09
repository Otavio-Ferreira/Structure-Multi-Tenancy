<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Spatie\RoleRequest;
use App\Services\Settings\Roles\RolesService;

class RolesController extends Controller
{
    private $rolesService;

    public function __construct(RolesService $rolesService)
    {
        $this->rolesService = $rolesService;
    }

    public function getRole($id)
    {
        return $this->rolesService->getRoleResponse($id);
    }

    public function getAllRoles()
    {
        return $this->rolesService->getAllRolesResponse();
    }

    public function store(RoleRequest $request)
    {
        return $this->rolesService->setRoleResponse($request);
    }

    public function update(RoleRequest $request, $id)
    {
        return $this->rolesService->updateRoleResponseResponse($request, $id);
    }
}
