<?php

namespace App\Http\Controllers\Api\Tenants\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Authentication\LoginRequest;
use App\Http\Requests\Api\Authentication\ResetRequest;
use App\Http\Requests\Api\Authentication\SendRequest;
use App\Models\Authentication\Tokens;
use App\Models\User;
use App\Services\Api\Login\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public $loginService;

    public function __construct(
        LoginService $loginService
    ) {
        $this->loginService = $loginService;
    }

    public function login(LoginRequest $request)
    {
        return $this->loginService->storeResponse($request);
    }

    public function send(SendRequest $request)
    {
        return $this->loginService->sendResponse($request);
    }

    public function reset(ResetRequest $request, Tokens $token)
    {
        return $this->loginService->updateResponse($request, $token);
    }

    public function register(ResetRequest $request, Tokens $token)
    {
        return $this->loginService->updateResponse($request, $token);
    }
}
