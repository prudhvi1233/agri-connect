<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS in production (e.g. Render) to fix Vite/CSS mixed content issues
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
