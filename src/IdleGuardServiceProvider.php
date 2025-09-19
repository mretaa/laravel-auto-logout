<?php

namespace Mretaa\AutoLogout;

use Illuminate\Support\ServiceProvider;

class IdleGuardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        // Publier la configuration
        $this->publishes([
            __DIR__.'/../config/idle-guard.php' => config_path('idle-guard.php'),
        ], 'config');

        // Publier la vue du modal
        $this->publishes([
            __DIR__.'/../resources/views/modal.blade.php' => resource_path('views/vendor/auto-logout/modal.blade.php'),
        ], 'views');

        // Publier le JS
        $this->publishes([
            __DIR__.'/../public/js/auto-logout.js' => public_path('vendor/auto-logout/js/auto-logout.js'),
        ], 'public');

        // Charger la vue
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'auto-logout');

        // Enregistrer la directive Blade
        \Mretaa\AutoLogout\AutoLogoutDirective::register();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/idle-guard.php', 'idle-guard'
        );
    }
}
