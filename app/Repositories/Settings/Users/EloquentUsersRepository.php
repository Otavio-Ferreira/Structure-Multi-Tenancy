<?php

namespace App\Repositories\Settings\Users;

use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EloquentUsersRepository implements UsersRepository
{
    
    public function getOneUser($id)
    {
        return User::find($id);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function set($request): User
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

            return $user;
        });

        return $user;
    }

    public function delete($id): void
    {
        DB::transaction(function () use ($id) {
            $register = $this->getOneUser($id);
            $register->delete();
        });
    }
}
