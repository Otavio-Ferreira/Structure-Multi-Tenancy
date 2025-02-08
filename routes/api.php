<?php

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Settings\UsersController;
use App\Models\Tenants\TenantApps;
use App\Models\User;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getApps', function (Request $request) {
    $apps = TenantApps::where("tenants_id", $request->tenants_id)->get();
    return response()->json($apps);
});

Route::post('login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    $credentials = $request->only(['email', 'password']);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->status == 1) {
            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso.',
                'data' => [
                    'user' => $user,
                    // 'token' => $user->createToken('authToken')->plainTextToken, // Gerar token se você estiver usando Sanctum ou Passport
                ],
            ], 200);
        } else {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Usuário inativo. Verifique com o administrador.',
            ], 403);
        }
    }

    return response()->json([
        'success' => false,
        'message' => 'Credenciais inválidas. Verifique o email e senha.',
    ], 401);
});


foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::post('login/enviar', [LoginController::class, 'store']);
        Route::post('login/solicitar', [LoginController::class, 'send']);
        Route::patch('login/atualizar/{token}', [LoginController::class, 'update'])->name('login.update');

        Route::middleware('auth:sanctum')->group(function () {
            Route::group(['middleware' => ['permission:adicionar_usuário']], function () {
                Route::get('users/getOne/{id}', [UsersController::class, 'getOneUser']);
                Route::get('users/getAll', [UsersController::class, 'getAllUsers']);
                Route::post('users/setUser', [UsersController::class, 'setUser']);
                Route::post('users/updateUser/{id}', [UsersController::class, 'update']);
                Route::delete('users/destroyUser/{id}', [UsersController::class, 'destroy']);
            });
        });
        // Route::middleware(Authenticate::class)->group(function () {

        //     Route::post('app/cadastrar', [AppsController::class, 'store'])->name('app.store');

        //     Route::post('app/atrelar', [TenantsAppsController::class, 'store'])->name('app.give');

        //     Route::post('tenant/cadastrar', [TenantsController::class, 'store'])->name('tenant.store');

        //     Route::group(['middleware' => ['auth', 'permission:adicionar_grupo']], function () {
        //         Route::get('gupos', [RolesController::class, 'index'])->name('roles.index');
        //         Route::post('grupos/adicionar', [RolesController::class, 'store'])->name('roles.store');
        //         Route::post('grupos/atualizar/{id}', [RolesController::class, 'update'])->name('roles.update');
        //     });

        // Route::group(['middleware' => ['auth', 'permission:adicionar_permissões']], function () {
        //     Route::get('permissoes', [PermissionsController::class, 'index'])->name('permissions.index');
        //     Route::post('permissoes/adicionar', [PermissionsController::class, 'store'])->name('permissions.store');
        //     Route::post('permissoes/atualizar/{id}', [PermissionsController::class, 'update'])->name('permissions.update');
        // });

        // Route::group(['middleware' => ['auth', 'permission:adicionar_usuário']], function () {
        //     Route::get('usuarios', [UsersController::class, 'index'])->name('users.index');
        //     Route::post('usuarios/adicionar', [UsersController::class, 'store'])->name('users.store');
        //     Route::post('usuarios/atualizar/{id}', [UsersController::class, 'update'])->name('users.update');
        //     Route::delete('usuarios/deletar/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
        // });

        Route::delete('users/logout', [UsersController::class, 'logout']);
        // });
    });
}
