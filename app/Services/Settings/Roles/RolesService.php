<?php

namespace App\Services\Settings\Roles;

use App\Http\Requests\Spatie\RoleRequest;
use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use App\Repositories\Settings\Roles\RolesRepository;

class RolesService
{
    protected $rolesRepository;

    public function __construct(
        RolesRepository $rolesRepository
    ) {
        $this->rolesRepository = $rolesRepository;
    }

    public function getRoleResponse($id){
        try {
            $role = $this->rolesRepository->getOneRole($id);
            if (!$role) {
                return response()->json([
                    "validate" => false,
                    "message" => "Essa role não existe."
                ], 204);
            }

            return response()->json([
                "validate" => true,
                "message" => "Esse role existe.",
                "role" => $role
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function getAllRolesResponse(){
        try {
            $roles = $this->rolesRepository->getAllRoles();

            return response()->json([
                "validate" => true,
                "message" => "Busca bem-sucedida.",
                "roles" => $roles
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function setRoleToUserResponse(User $user, StoreRequest $request){
        try {
            $this->rolesRepository->setRoleToUser($user, $request);
            return response()->json([
                "validate" => true,
                "message" => "Role adicionado ao usuário com sucesso."
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function setRoleResponse(RoleRequest $request){
        try {
            $role = $this->rolesRepository->setRole($request);
            return response()->json([
                "validate" => true,
                "message" => "Role criada com sucesso.",
                "role" => $role
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function updateRoleResponseResponse(RoleRequest $request, $id){
        try {
            $role = $this->rolesRepository->updateRole($request, $id);

            return response()->json([
                "validate" => true,
                "message" => "Role atualizada com sucesso.",
                "role" => $role
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }
}
