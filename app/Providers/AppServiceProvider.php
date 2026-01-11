<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
use App\Services\Format;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

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
        Gate::define('update-settings', function (User $user) {
            return $user->hasPerm('settings.edit');
        });
        Blade::directive('vite_legacy', function ($entry) {
            return "<?php echo \\App\\Services\\Format::legacyScriptTag($entry); ?>";
        });
        if ($this->app->runningInConsole()) return;
        if (request()->getHost() === config('app.user_domain')) {
            View::share('siteContext', 'user');
        } elseif (request()->getHost() === config('app.admin_domain')) {
            View::share('siteContext', 'admin');
        }
        /*
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        */
    }
}
