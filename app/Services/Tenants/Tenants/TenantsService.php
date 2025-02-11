<?php

namespace App\Services\Tenants\Tenants;

use App\Events\Tenants\TenantCreated;
use App\Http\Requests\Tenants\StoreRequest;
use App\Http\Requests\Tenants\UpdateRequest;
use App\Repositories\Authentication\LoginRepository;
use App\Repositories\Settings\Roles\RolesRepository;
use App\Repositories\Settings\Users\UsersRepository;
use App\Repositories\Tenants\Tenants\TenantsRepository;
use App\Services\Settings\Users\UserService;
use Illuminate\Cache\Events\RetrievingKey;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantsService
{
    protected $tenantsRepository;
    protected $usersRepository;
    protected $loginRepository;
    protected $rolesRepository;
    protected $userService;

    public function __construct(
        TenantsRepository $tenantsRepository,
        UsersRepository $usersRepository,
        LoginRepository $loginRepository,
        RolesRepository $rolesRepository,
        UserService $userService,
    ) {
        $this->tenantsRepository = $tenantsRepository;
        $this->usersRepository = $usersRepository;
        $this->loginRepository = $loginRepository;
        $this->rolesRepository = $rolesRepository;
        $this->userService = $userService;
    }

    public function getTenantResponse($id)
    {
        try {
            $tenant = $this->tenantsRepository->getTenant($id);
            if (!$tenant) {
                return response()->json([
                    "validate" => false,
                    "message" => "Esse tenant não existe."
                ], 204);
            }

            return response()->json([
                "validate" => true,
                "message" => "Esse tenant existe.",
                "tenant" => $tenant
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function getAllTenantsResponse()
    {
        try {
            $tenants = $this->tenantsRepository->getAllTenants();

            return response()->json([
                "validate" => true,
                "message" => "Busca bem-sucedida.",
                "tenants" => $tenants
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function setTenantsResponse(StoreRequest $request)
    {
        try {
            $tenant = DB::transaction(function () use ($request) {

                $tenant = $this->tenantsRepository->createTenant($request);
                $tenant->domains()->create(['domain' => $request->domain . ".localhost"]);
                $this->tenantsRepository->setDinamicTenantDatabase($tenant);


                $permissions = [
                    "1" => "adicionar_grupo",
                    "2" => "adicionar_usuário",
                    "3" => "ver_dashboard",
                ];

                foreach ($permissions as $permission) {
                    Permission::firstOrCreate([
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

                $this->dispatchTenantCreated($data, $tenant, $request->route);

                $this->tenantsRepository->destroyDinamicTenantDatabase();

                return $tenant;
            });

            if ($tenant) {
                return redirect()->back()->with("toast_success", "Tenant criado com sucesso.");
                // return response()->json([
                //     "validate" => true,
                //     "message" => "Tenant criado com sucesso.",
                //     "tenant" => $tenant
                // ], 201);
            } else {
                return redirect()->back()->with("toast_error", "Erro ao criar tenant.");
                // return response()->json([
                //     "validate" => false,
                //     "message" => "Erro no servidor."
                // ], 500);
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with("toast_error", "Erro ao criar tenant.");

            // return response()->json([
            //     "validate" => false,
            //     "message" => "Erro no servidor."
            // ], 500);
        }
    }

    public function updateTenantsResponse(UpdateRequest $request, $id)
    {
        try {
            $tenant = $this->tenantsRepository->updateTenant($request, $id);
            $this->tenantsRepository->setDinamicTenantDatabase($tenant);

            return redirect()->back()->with("toast_success", "Tenant atualizado com sucesso.");

            // return response()->json([
            //     "validate" => true,
            //     "message" => "Tenant atualizado com sucesso.",
            //     "tenant" => $tenant
            // ], 200);
        } catch (\Throwable $th) {
            return redirect()->back()->with("toast_error", "Erro ao atualizar tenant.");
            // return response()->json([
            //     "validate" => false,
            //     "message" => "Erro no servidor."
            // ], 500);
        }
    }

    public function destroyTenantResponse($id)
    {
        try {
            $this->tenantsRepository->destroyTenant($id);
            return redirect()->back()->with("toast_success", "Tenant deletado com sucesso.");

            // return response()->json([
            //     "validate" => true,
            //     "message" => "Usuário deletado com sucesso."
            // ], 200);
        } catch (\Throwable $th) {
            return redirect()->back()->with("toast_error", "Erro ao deletar tenant.");

            // return response()->json([
            //     "validate" => false,
            //     "message" => "Erro no servidor."
            // ], 500);
        }
    }

    public function dispatchTenantCreated($data, $tenant, $route)
    {
        TenantCreated::dispatch(
            $data['name'],
            $data['email'],
            $data['time'],
            $data['token'],
            $data['title'],
            $tenant->id,
            $route . '/' . $data['token']
        );
    }
}
