<?php

namespace App\Modules\Menus\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'menus');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'menus');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'menus');
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
