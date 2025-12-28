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
        if (!app()->runningInConsole() && \Illuminate\Support\Facades\Schema::hasTable('parametres_systeme')) {
            $settings = \App\Models\ParametreSysteme::all()->pluck('valeur', 'cle')->toArray();
            view()->share('siteSettings', $settings);
        }
    }
}
