<?php

namespace App\Http\Controllers\Api\Tenants\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid credentials'
                ], 401);
            }

            $token = $this->getToken($user);

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Could not cretae token'
            ], 500);
        }
    }

    public function getToken(User $user){
        return JWTAuth::claims([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tenant_id' => tenant()->id
        ])->fromUser($user);
    }
    
}
