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
                    "success" => false,
                    "message" => "Esse grupo não existe."
                ], 204);
            }

            return response()->json([
                "success" => true,
                "message" => "Esse grupo existe.",
                "role" => $role
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Erro no servidor.",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function getAllRolesResponse(){
        try {
            $roles = $this->rolesRepository->getAllRoles();

            return response()->json([
                "success" => true,
                "message" => "Busca bem-sucedida.",
                "roles" => $roles
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Erro no servidor.",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function setRoleToUserResponse(User $user, $request){
        try {
            $this->rolesRepository->setRoleToUser($user, $request);
            return response()->json([
                "success" => true,
                "message" => "Grupo adicionado ao usuário com sucesso."
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Erro no servidor.",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function setRoleResponse($request){
        try {
            $role = $this->rolesRepository->setRole($request);
            return response()->json([
                "success" => true,
                "message" => "Grupo criada com sucesso.",
                "role" => $role
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Erro no servidor.",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function updateRoleResponseResponse($request, $id){
        try {
            $role = $this->rolesRepository->updateRole($request, $id);

            return response()->json([
                "success" => true,
                "message" => "Grupo atualizado com sucesso.",
                "role" => $role
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
