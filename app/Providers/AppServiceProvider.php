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
        // Force HTTPS in production when behind a proxy (like Render)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');

            // Trust proxies for HTTPS detection
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}
