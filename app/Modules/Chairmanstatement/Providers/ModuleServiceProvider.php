<?php

namespace App\Modules\Chairmanstatement\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'chairmanstatement');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'chairmanstatement');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'chairmanstatement');
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
