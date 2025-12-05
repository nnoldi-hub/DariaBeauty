<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Share settings with all views
        view()->composer('*', function ($view) {
            $settings = \App\Models\Setting::getAll();
            $view->with('settings', $settings);
        });
    }
}