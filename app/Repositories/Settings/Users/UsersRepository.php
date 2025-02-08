<?php

namespace App\Repositories\Settings\Users;

use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

interface UsersRepository{
    public function set(StoreRequest $request) : User;

    public function delete($id) : void;

    public function setUserTenant(Request $request) :User;

    public function getOneUser($id);

    public function getAllUsers();
}