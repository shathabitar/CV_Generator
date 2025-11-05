<?php

namespace App\Providers;

use App\Services\PerformanceService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services
        $this->app->singleton(PerformanceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for older MySQL versions
        Schema::defaultStringLength(191);

        // Enable performance monitoring in local environment
        if ($this->app->environment('local')) {
            PerformanceService::monitorSlowQueries();
        }
    }
}
