<?php

namespace App\Providers;

use App\Repositories\Interfaces;
use App\Repositories;
use App\Services;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Interfaces\FooRepositoryInterface::class, Repositories\FooRepository::class);
        $this->app->bind(Interfaces\AuthenticationRepositoryInterface::class, Repositories\AuthenticationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
