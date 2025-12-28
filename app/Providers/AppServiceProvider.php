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

        // Authentication Logging
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            \App\Models\LogActivite::create([
                'user_id' => $event->user->id,
                'action' => 'Connexion',
                'details' => json_encode(['email' => $event->user->email, 'status' => 'success']),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if ($event->user) {
                \App\Models\LogActivite::create([
                    'user_id' => $event->user->id,
                    'action' => 'Déconnexion',
                    'details' => json_encode(['email' => $event->user->email]),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });
    }
}
