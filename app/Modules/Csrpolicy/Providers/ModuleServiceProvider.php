<?php

namespace App\Modules\Csrpolicy\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'csrpolicy');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'csrpolicy');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'csrpolicy');
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
