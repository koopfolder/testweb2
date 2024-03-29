<?php

namespace App\Modules\Whatfranchise\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'whatfranchise');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'whatfranchise');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'whatfranchise');
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
