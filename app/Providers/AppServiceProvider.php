<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

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
        Blade::directive('scriptLegacy', function ($entry) {
            if (!app()->environment('production')) {
                return '';
            }
            $manifestPath = public_path('build/manifest.json');
            $filename = '';

            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $entry = trim($entry, "'\""); 
                if (isset($manifest[$entry])) {
                    $filename = $manifest[$entry]['file'];
                }
            }
            if (true || $filename) {
                return '<script nomodule defer src="' . asset('build/' . $filename) . '"></script>';
            }
            return '';
        });

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
