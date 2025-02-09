<?php

namespace App\Providers\Apps;

use App\Repositories\Apps\AppsRepository;
use App\Repositories\Apps\EloquentAppsRepository;
use Illuminate\Support\ServiceProvider;

class AppsRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AppsRepository::class, EloquentAppsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
