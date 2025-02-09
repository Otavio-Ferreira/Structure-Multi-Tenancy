<?php

use App\Http\Controllers\Apps\AppsController;
use App\Http\Controllers\Apps\TenantsAppsController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Settings\RolesController;
use App\Http\Controllers\Settings\UsersController;
use App\Http\Controllers\Tenants\TenantsController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::post('login/enviar', [LoginController::class, 'store']);
        Route::post('login/solicitar', [LoginController::class, 'send']);
        Route::patch('login/atualizar/{token}', [LoginController::class, 'update']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::group(['middleware' => ['permission:adicionar_usuário']], function () {
                Route::get('users/getOne/{id}', [UsersController::class, 'getOneUser']);
                Route::get('users/getAll', [UsersController::class, 'getAllUsers']);
                Route::post('users/setUser', [UsersController::class, 'setUser']);
                Route::post('users/updateUser/{id}', [UsersController::class, 'update']);
                Route::delete('users/destroyUser/{id}', [UsersController::class, 'destroy']);
            });
            Route::group(['middleware' => ['auth', 'permission:adicionar_grupo']], function () {
                Route::get('roles/getRole/{id}', [RolesController::class, 'getRole']);
                Route::get('roles/getAllRoles', [RolesController::class, 'getAllRoles']);
                Route::post('roles/setRole', [RolesController::class, 'store']);
                Route::patch('roles/updateRole/{id}', [RolesController::class, 'update']);
            });
            Route::group(['middleware' => ['auth', 'permission:adicionar_apps']], function () {
                Route::get('app/getApp/{id}', [AppsController::class, 'getApp']);
                Route::get('app/getApps', [AppsController::class, 'getApps']);
                Route::post('app/setApp', [AppsController::class, 'setApp']);
                Route::patch('app/updateApp/{id}', [AppsController::class, 'updateApp']);
            });

            Route::group(['middleware' => ['auth', 'permission:adicionar_tenants']], function () {
                Route::get('tenant/getTenant/{id}', [TenantsController::class, 'getTenant']);
                Route::get('tenant/getTenants', [TenantsController::class, 'getTenants']);
                Route::post('tenant/setTenant', [TenantsController::class, 'setTenant']);
                Route::patch('tenant/updateTenant/{id}', [TenantsController::class, 'updateTenant']);
                Route::delete('tenant/deleteTenant/{id}', [TenantsController::class, 'destroyTenant']);

                // Route::post('app/setAppsToTenant', [TenantsAppsController::class, 'setAppsToTenant']);
                // Route::post('app/atrelar', [TenantsAppsController::class, 'store'])->name('app.give');
            });

            Route::delete('users/logout', [UsersController::class, 'logout']);
        });
    });
}
