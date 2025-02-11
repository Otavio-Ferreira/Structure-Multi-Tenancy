<?php

use App\Http\Controllers\Api\Tenants\Authentication\LoginController;
use App\Models\Tenants\TenantApps;
use App\Models\User;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([
    InitializeTenancyByRequestData::class,
])->group(function(){
    Route::get('/identifyTenant', function(){
        $user = User::all();

        if($user){
            return response()->json([
                'success' => true,
                'tenant' => $user
            ]
            );
        }
    });

    Route::post('login', [LoginController::class, 'login']);
    
});


Route::middleware(['api', 'identify.tenant.jwt'])->group(function () {

    Route::get('/getUsers', function (Request $request) {
        try {
            $users = User::all();
            return response()->json([
                'success' => true,
                'users' => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    });

});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('getApps', function (Request $request) {
//     $apps = TenantApps::where("tenants_id", $request->tenants_id)->get();
//     return response()->json($apps);
// });

// Route::post('login', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required|string|min:6',
//     ]);

//     $credentials = $request->only(['email', 'password']);

//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();

//         if ($user->status == 1) {
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Login realizado com sucesso.',
//                 'data' => [
//                     'user' => $user,
//                     // 'token' => $user->createToken('authToken')->plainTextToken, // Gerar token se você estiver usando Sanctum ou Passport
//                 ],
//             ], 200);
//         } else {
//             Auth::logout();
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Usuário inativo. Verifique com o administrador.',
//             ], 403);
//         }
//     }

//     return response()->json([
//         'success' => false,
//         'message' => 'Credenciais inválidas. Verifique o email e senha.',
//     ], 401);
// });
