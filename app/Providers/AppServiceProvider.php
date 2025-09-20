<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

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
        /*
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        */
        if ($this->app->runningInConsole()) return;
        if (request()->getHost() === config('app.user_domain')) {
            View::share('siteContext', 'user');
        } elseif (request()->getHost() === config('app.admin_domain')) {
            View::share('siteContext', 'admin');
        }
    }
}
