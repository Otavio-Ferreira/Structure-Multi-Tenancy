<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Services\Settings\Users\UserService;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $userService;

    public function __construct(
        UserService $userService,
    ) {
        $this->userService = $userService;
    }

    public function getOneUser($id)
    {
        return $this->userService->getOneUserResponse($id);
    }

    public function getAllUsers()
    {
        return $this->userService->getAllUserResponse();
    }

    public function setUser(StoreRequest $request)
    {
        return $this->userService->setUserResponse($request);
    }

    public function update(UpdateRequest $request, $id)
    {
        return $this->userService->updateUserResponse($request, $id);
    }

    public function destroy($id)
    {
        return $this->userService->destroyUserResponse($id);
    }

    public function logout(Request $request)
    {
        return $this->userService->logoutUserResponse($request);
    }
}
