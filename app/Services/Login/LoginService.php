<?php

namespace App\Services\Login;

use App\Events\Autenticator\TokenCreated;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\ResetRequest;
use App\Http\Requests\Authentication\SendRequest;
use App\Models\Authentication\Tokens;
use App\Models\User;
use App\Repositories\Authentication\LoginRepository;
use Illuminate\Support\Facades\Hash;

class LoginService {
    public $repository;

    public function __construct(LoginRepository $repository)
    {
        $this->repository = $repository;
    }

    public function storeResponse(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    "validate" => false,
                    "message" => "Credenciais inválidas."
                ], 401);
            }

            $token = $user->createToken('create_users_token')->plainTextToken;

            return response()->json([
                "validate" => true,
                "message" => "Autenticação bem-sucedida.",
                "token" => $token,
                'token_type' => 'Bearer',
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function sendResponse(SendRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $data = $this->repository->createToken($user, "reset_password");
                $this->dispatchTokenCreated($data, $request->route);
               
                return response()->json([
                    "validate" => true,
                    "message" => "Solicitação de email bem-sucedida.",
                ], 200);
            } else {
                return response()->json([
                    "validate" => false,
                    "message" => "Credenciais inválidas."
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function updateResponse(ResetRequest $request, Tokens $token)
    {
        try {
            if (isset($token) && $token->status == 1) {
                $this->repository->changePassword($request, $token);
                return response()->json([
                    "validate" => true,
                    "message" => "Mudança de senha bem-sucedida.",
                ], 200);
            } else {
                return response()->json([
                    "validate" => false,
                    "message" => "As duas senhas precisam ser iguais."
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function dispatchTokenCreated($data, $domain){
        TokenCreated::dispatch(
            $data['name'],
            $data['email'],
            $data['time'],
            $data['token'],
            $data['title'],
            $domain . '/' . $data['token']
        );
    }
}
