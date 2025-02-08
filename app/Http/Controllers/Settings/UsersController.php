<?php

namespace App\Http\Controllers\Settings;

use App\Events\Autenticator\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\User;
use App\Repositories\Authentication\LoginRepository;
use App\Repositories\Settings\Roles\RolesRepository;
use App\Repositories\Settings\Users\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    private $data = [];
    private $usersRepository;
    private $rolesRepository;
    private $loginRepository;

    public function __construct(UsersRepository $usersRepository, RolesRepository $rolesRepository, LoginRepository $loginRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->rolesRepository = $rolesRepository;
        $this->loginRepository = $loginRepository;
    }

    public function getOneUser($id)
    {
        return $this->usersRepository->getOneUser($id);
    }

    public function getAllUsers()
    {
        return $this->usersRepository->getAllUsers();
    }

    public function setUser(StoreRequest $request)
    {
        try {
            $user = $this->usersRepository->set($request);
            $this->rolesRepository->set($user, $request);
            $data = $this->loginRepository->createToken($user, "first_access");

            UserCreated::dispatch(
                $data['name'],
                $data['email'],
                $data['time'],
                $data['token'],
                $data['title'],
                $request->route . '/' . $data['token']
            );

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

    public function update(UpdateRequest $request, $id)
    {
        try {
            $user = User::find($id);

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

    public function destroy($id)
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

    public function logout(Request $request)
    {
        dd($request->user()); 
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
}
