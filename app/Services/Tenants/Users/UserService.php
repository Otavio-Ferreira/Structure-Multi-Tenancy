<?php

namespace App\Services\Settings\Users;

use App\Events\Autenticator\UserCreated;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Repositories\Authentication\LoginRepository;
use App\Repositories\Settings\Roles\RolesRepository;
use App\Repositories\Settings\Users\UsersRepository;
use Illuminate\Http\Request;

class UserService
{
    private $usersRepository;
    private $rolesRepository;
    private $loginRepository;

    public function __construct(
        UsersRepository $usersRepository, 
        RolesRepository $rolesRepository, 
        LoginRepository $loginRepository, 
    )
    {
        $this->usersRepository = $usersRepository;
        $this->rolesRepository = $rolesRepository;
        $this->loginRepository = $loginRepository;
    }

    public function getOneUserResponse($id)
    {
        try {

            $user = $this->usersRepository->getOneUser($id);
            if (!$user) {
                return response()->json([
                    "validate" => false,
                    "message" => "Esse usuário não existe."
                ], 204);
            }

            return response()->json([
                "validate" => true,
                "message" => "Esse usuário existe.",
                "user" => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function getAllUserResponse()
    {
        try {
            $users = $this->usersRepository->getAllUsers();

            return response()->json([
                "validate" => true,
                "message" => "Busca bem-sucedida.",
                "users" => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function setUserResponse(StoreRequest $request)
    {
        try {
            $user = $this->usersRepository->set($request);

            $this->rolesRepository->setRoleToUser($user, $request);

            $data = $this->loginRepository->createToken($user, "first_access");

            $this->dispatchUserCreated($data, $request->route);

            return response()->json([
                "validate" => true,
                "message" => "Usuário criado com sucesso.",
                "user" => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function updateUserResponse(UpdateRequest $request, $id)
    {
        try {
            $user = $this->usersRepository->getOneUser($id);

            $user->name = $request->name;
            $user->status = $request->status;

            $user->save();

            if ($request->role) {
                $user->syncRoles([$request->role]);
            }

            return response()->json([
                "validate" => true,
                "message" => "Usuário atualizado com sucesso.",
                "user" => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function destroyUserResponse($id)
    {
        try {
            $this->usersRepository->delete($id);
            return response()->json([
                "validate" => true,
                "message" => "Usuário deletado com sucesso."
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function logoutUserResponse(Request $request){
        try {
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Logout realizado com sucesso.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function dispatchUserCreated(array $data, string $route)
    {
        UserCreated::dispatch(
            $data['name'],
            $data['email'],
            $data['time'],
            $data['token'],
            $data['title'],
            $route . '/' . $data['token']
        );
    }
}
