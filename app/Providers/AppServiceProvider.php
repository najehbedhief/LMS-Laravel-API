<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\RoleRequestRepository;
use App\Repositories\Interfaces\RoleRequestInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            RoleRequestInterface::class,
            RoleRequestRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
