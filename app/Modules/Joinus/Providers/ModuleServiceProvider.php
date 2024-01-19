<?php

namespace App\Modules\Joinus\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'joinus');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'joinus');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'joinus');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
