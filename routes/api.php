<?php

use App\Http\Controllers\Api\Roles\RolesController;
use App\Http\Controllers\Api\Tenants\Authentication\LoginController;
use App\Http\Controllers\Api\Users\UsersController;
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

// IDENTIFICA O TENANT PELO ID

Route::middleware([
    InitializeTenancyByRequestData::class,
])->group(function(){

    Route::post('login', [LoginController::class, 'login']);
    Route::post('login/enviar', [LoginController::class, 'send']);
    Route::patch('login/atualizar/{token}', [LoginController::class, 'reset']);
    Route::post('login/registrar/{token}', [LoginController::class, 'register']);
    
});

// IDENTIFICA O TENANT PELO JWT

Route::middleware(['api', 'identify.tenant.jwt'])->group(function () {

    Route::group(['middleware' => ['permission:adicionar_usuário']], function () {
        Route::get('usuarios/selecionar/{id}', [UsersController::class, 'getUser']);
        Route::get('usuarios/todos', [UsersController::class, 'getUsers']);
        Route::post('usuarios/adicionar', [UsersController::class, 'setUser']);
        Route::patch('usuarios/atualizar/{id}', [UsersController::class, 'update']);
        Route::delete('usuarios/deletar/{id}', [UsersController::class, 'destroy']);
    });

    Route::group(['middleware' => ['permission:adicionar_grupo']], function () {
        Route::get('grupos/selecionar/{id}', [RolesController::class, 'getRole']);
        Route::get('grupos/todos', [RolesController::class, 'getAllRoles']);
        Route::post('grupos/adicionar', [RolesController::class, 'store']);
        Route::patch('grupos/atualizar/{id}', [RolesController::class, 'update']);
    });

});