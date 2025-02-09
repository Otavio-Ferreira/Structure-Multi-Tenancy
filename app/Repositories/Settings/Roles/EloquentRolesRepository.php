<?php

namespace App\Repositories\Settings\Roles;

use App\Http\Requests\Spatie\RoleRequest;
use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class EloquentRolesRepository implements RolesRepository
{

    public function getOneRole($id): Role
    {
        return Role::find($id);
    }

    public function getAllRoles() :Collection
    {
        return Role::all();
    }

    public function setRoleToUser(User $user, StoreRequest $request): void
    {
        DB::transaction(function () use ($user, $request) {
            $user->assignRole($request->role);
        });
    }

    public function setRole(RoleRequest $request): Role
    {
        $role = DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name
            ]);
            $role->givePermissionTo([$request->permission_selected]);
            return $role;
        });

        return $role;
    }

    public function updateRole(RoleRequest $request, $id): Role
    {
        $role = DB::transaction(function () use ($request, $id) {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();
            $selectedPermissions = $request->input('permission_selected', []);
            $role->syncPermissions($selectedPermissions);
            return $role;
        });

        return $role;
    }
}
