<?php

namespace App\Providers\Tenants\Tenants;

use App\Repositories\Tenants\Tenants\EloquentTenantsRepository;
use App\Repositories\Tenants\Tenants\TenantsRepository;
use Illuminate\Support\ServiceProvider;

class TenantsRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TenantsRepository::class, EloquentTenantsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
