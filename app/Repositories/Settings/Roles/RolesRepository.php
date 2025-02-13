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

    public function setRoleToUser(User $user, $request) :void;

    public function setRole($request) :Role;

    public function updateRole($request, $id) :Role;
}