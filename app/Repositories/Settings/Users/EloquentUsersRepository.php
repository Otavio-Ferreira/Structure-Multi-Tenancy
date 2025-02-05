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
    
    public function set(StoreRequest $request) : User
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

    public function setUserTenant(Request $request) : User
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

    public function delete($id) : void
    {
        DB::transaction(function () use ($id) {
            $register = User::find($id);
            $register->delete();
        });
    }
}
