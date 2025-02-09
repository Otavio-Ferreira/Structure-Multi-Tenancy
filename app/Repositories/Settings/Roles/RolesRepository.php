<?php 

namespace App\Repositories\Settings\Roles;

use App\Http\Requests\Spatie\RoleRequest;
use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

interface RolesRepository{
    public function getOneRole($id) :Role;

    public function getAllRoles() :Collection;

    public function setRoleToUser(User $user, StoreRequest $request) :void;

    public function setRole(RoleRequest $request) :Role;

    public function updateRole(RoleRequest $request, $id) :Role;
}