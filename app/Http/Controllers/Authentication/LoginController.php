<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\ResetRequest;
use App\Http\Requests\Authentication\SendRequest;
use App\Models\Authentication\Tokens;
use App\Services\Login\LoginService;

class LoginController extends Controller
{
    public $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function store(LoginRequest $request)
    {
        return $this->loginService->storeResponse($request);
    }

    public function send(SendRequest $request)
    {
        return $this->loginService->sendResponse($request);
    }

    public function update(ResetRequest $request, Tokens $token)
    {
        $this->loginService->updateResponse($request, $token);
    }
}
