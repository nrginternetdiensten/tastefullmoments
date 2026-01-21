<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Laravel Boost only in local environment
        if ($this->app->environment('local')) {
            if (class_exists(\Laravel\Boost\BoostServiceProvider::class)) {
                $this->app->register(\Laravel\Boost\BoostServiceProvider::class);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure the `layouts` view namespace points to the components layouts
        // directory so anonymous/component-based layout references resolve.
        View::addNamespace('layouts', resource_path('views/components/layouts'));
    }
}
