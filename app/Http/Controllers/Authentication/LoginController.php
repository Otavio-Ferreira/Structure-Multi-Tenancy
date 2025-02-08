<?php

namespace App\Http\Controllers\Authentication;

use App\Events\Autenticator\TokenCreated;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Autenticator;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\ResetRequest;
use App\Http\Requests\Authentication\SendRequest;
use App\Models\Authentication\Tokens;
use App\Models\User;
use App\Repositories\Authentication\LoginRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public $repository;

    public function __construct(LoginRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('pages.authentication.index');
    }

    public function store(LoginRequest $request)
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

    public function reset()
    {
        return view('pages.authentication.reset');
    }

    public function edit(Tokens $token)
    {
        if (isset($token) && $token->status == 1) {
            return view('pages.authentication.edit')->with('token_id', $token->id);
        }

        return to_route('login');
    }

    public function send(SendRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $data = $this->repository->createToken($user, "reset_password");

                TokenCreated::dispatch(
                    $data['name'],
                    $data['email'],
                    $data['time'],
                    $data['token'],
                    $data['title'],
                    $request->route . '/' . $data['token']
                );
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

    public function update(ResetRequest $request, Tokens $token)
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

    public function register(Tokens $token)
    {
        if (isset($token) && $token->status == 1) {
            return view('pages.authentication.register')->with('token_id', $token->id);
        }

        return to_route('login');
    }
}
