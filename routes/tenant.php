<?php

declare(strict_types=1);

use App\Http\Controllers\Authentication\LoginTenantController;
use App\Http\Controllers\Settings\RolesController;
use App\Http\Controllers\Settings\UsersController;
use App\Http\Controllers\System\HomeController;
use App\Http\Controllers\Tenants\TenantsHomeController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', function () {
        return to_route('login');
    });

    Route::get('login', [LoginTenantController::class, 'index'])->name('login');
    Route::post('login/enviar', [LoginTenantController::class, 'store'])->name('login.store');

    Route::get('login/resetar', [LoginTenantController::class, 'reset'])->name('login.reset');
    Route::post('login/solicitar', [LoginTenantController::class, 'send'])->name('login.send');

    Route::get('login/editar/{token}', [LoginTenantController::class, 'edit'])->name('login.edit');
    Route::get('login/registrar/{token}', [LoginTenantController::class, 'register'])->name('login.register');
    Route::post('login/atualizar/{token}', [LoginTenantController::class, 'update'])->name('login.update');

    Route::middleware(Authenticate::class)->group(function () {
        // Route::get('/dashboard', function() {
        //     return "Tenant logado";
        // })->name('home.index');
        Route::get('/dashboard', [TenantsHomeController::class, 'index'])->name('home.index');
        // Route::post('/dashboard', [HomeController::class, 'index']);

        // Route::post('app/cadastrar', [AppsController::class, 'store'])->name('app.store');

        // Route::post('app/atrelar', [TenantsAppsController::class, 'store'])->name('app.give');

        // Route::post('tenant/cadastrar', [TenantsController::class, 'store'])->name('tenant.store');

        Route::group(['middleware' => ['auth', 'permission:adicionar_grupo']], function () {
            Route::get('grupos', [RolesController::class, 'index'])->name('roles.index');
            Route::post('grupos/adicionar', [RolesController::class, 'store'])->name('roles.store');
            Route::post('grupos/atualizar/{id}', [RolesController::class, 'update'])->name('roles.update');
        });

        // Route::group(['middleware' => ['auth', 'permission:adicionar_permissões']], function () {
        //     Route::get('permissoes', [PermissionsController::class, 'index'])->name('permissions.index');
        //     Route::post('permissoes/adicionar', [PermissionsController::class, 'store'])->name('permissions.store');
        //     Route::post('permissoes/atualizar/{id}', [PermissionsController::class, 'update'])->name('permissions.update');
        // });

        Route::group(['middleware' => ['auth', 'permission:adicionar_usuário']], function () {
            Route::get('usuarios', [UsersController::class, 'index'])->name('users.index');
            Route::post('usuarios/adicionar', [UsersController::class, 'store'])->name('users.store');
            Route::post('usuarios/atualizar/{id}', [UsersController::class, 'update'])->name('users.update');
            Route::delete('usuarios/deletar/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
        });

        Route::get('users/sair', [UsersController::class, 'logout'])->name('logout');
    });






    // Route::get('/', function () {

    //     return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('name');
    // });

    // Route::get('login/registrar/{token}', [LoginController::class, 'register'])->name("login.register");
    // Route::get('login/registrar/{token}', function () {
    //     return view('pages.authentication.index');
    //     return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('name');
    // })->name("login.register");
});
