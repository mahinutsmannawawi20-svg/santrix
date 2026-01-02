<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share pesantren name to login view
        // Fallback to first pesantren if not specific context (temp solution for single-tenant feel)
        \Illuminate\Support\Facades\View::composer('auth.login', function ($view) {
            $pesantren = \App\Models\Pesantren::first();
            $view->with('pesantren_nama', $pesantren ? $pesantren->nama : null);
        });
    }
}
