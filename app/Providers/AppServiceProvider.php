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
        try {
            $mainSettings = \App\Models\Setting::get('main', []);
            $siteTitle = !empty($mainSettings['title']) ? $mainSettings['title'] : config('app.name', 'NewLink');
            config(['app.name' => $siteTitle]);
            
            \Illuminate\Support\Facades\View::share('siteTitle', $siteTitle);
            \Illuminate\Support\Facades\View::share('mainSettings', $mainSettings);
        } catch (\Exception $e) {
            // Silently ignore during migration/bootstrapping
        }
    }
}
