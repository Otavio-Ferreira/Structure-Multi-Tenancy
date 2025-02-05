<?php

namespace App\Http\Controllers\Tenants;

use App\Events\Autenticator\UserCreated;
use App\Events\Tenants\TenantCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\StoreRequest;
use App\Models\Tenants\Tenant;
use App\Repositories\Authentication\LoginRepository;
use App\Repositories\Settings\Roles\RolesRepository;
use App\Repositories\Settings\Users\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantsController extends Controller
{
    private $data = [];
    private $usersRepository;
    private $loginRepository;
    private $rolesRepository;

    public function __construct(UsersRepository $usersRepository, LoginRepository $loginRepository, RolesRepository $rolesRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->loginRepository = $loginRepository;
        $this->rolesRepository = $rolesRepository;
    }
    public function store(StoreRequest $request)
    {
        try {
            $retur = DB::transaction(function () use ($request) {
                $tenant = Tenant::create([
                    "name" => $request->name,
                    "tenancy_db_name" => $request->tenancy_db_name,
                    "tenancy_db_host" => $request->tenancy_db_host,
                    "tenancy_db_user" => $request->tenancy_db_user,
                    "tenancy_db_password" => $request->tenancy_db_password,
                    "tenancy_db_port" => $request->tenancy_db_port,
                ]);

                $tenant->domains()->create(['domain' => $request->domain . ".localhost"]);

                Config::set('database.connections.tenant', [
                    'driver' => 'mysql',
                    'host' => $tenant->tenancy_db_host,
                    'port' => $tenant->tenancy_db_port,
                    'database' => $tenant->tenancy_db_name,
                    'username' => $tenant->tenancy_db_user,
                    'password' => $tenant->tenancy_db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'strict' => true,
                    'engine' => null,
                ]);

                // Conectar ao banco de dados do tenant
                Config::set('database.default', 'tenant');
                DB::purge(); // Limpa a conexão padrão
                DB::reconnect(); // Reconecta com a nova configuração

                $permissions = [
                    "1" => "adicionar_grupo",
                    "2" => "adicionar_usuário",
                    "3" => "ver_dashboard",
                ];

                foreach ($permissions as $permission) {
                    Permission::create([
                        'name' => $permission,
                        'guard_name' => 'web'
                    ]);
                }

                $role = Role::create([
                    'name' => 'Gerente',
                    'guard_name' => 'web'
                ]);

                $role->givePermissionTo([
                    "adicionar_grupo",
                    "adicionar_usuário",
                    "ver_dashboard"
                ]);


                $user = $this->usersRepository->setUserTenant($request);
                $user->assignRole($role);

                $data = $this->loginRepository->createToken($user, "first_access");

                TenantCreated::dispatch(
                    $data['name'],
                    $data['email'],
                    $data['time'],
                    $data['token'],
                    $data['title'],
                    $tenant->id,
                    $tenant->domain->domain,
                );

                Config::set('database.default', 'mysql');
                DB::purge();
                DB::reconnect();

                return 1;
            });

            if ($retur) {
                return redirect()->back()->with("toast_success", "Tenant cadastrado com sucesso!");
            } else {
                return redirect()->back()->with("toast_error", "Erro ao cadastrar tenant!");
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with("toast_error", "Erro ao cadastrar tenant!");
        }
    }
}
