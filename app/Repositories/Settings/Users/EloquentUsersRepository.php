<?php

namespace App\Repositories\Settings\Users;

use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EloquentUsersRepository implements UsersRepository
{
    // protected $connection;

    // public function setConnection($connection)
    // {
    //     $this->connection = $connection;
    //     return $this;
    // }

    public function getOneUser($id)
    {
        try {
            $user = User::find($id);
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

    public function getAllUsers()
    {
        try {
            $users = User::all();

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

    public function set(StoreRequest $request): User
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "status" => 0,
            ]);

            return $user;
        });

        return $user;
    }

    public function setUserTenant(Request $request): User
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "status" => 0,
            ]);

            // if ($this->connection) {
            //     DB::setDefaultConnection('mysql');
            // }

            return $user;
        });

        return $user;
    }

    public function delete($id): void
    {
        DB::transaction(function () use ($id) {
            $register = User::find($id);
            $register->delete();
        });
    }
}
