<?php

namespace App\Modules\Experienceawards\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'experienceawards');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'experienceawards');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'experienceawards');
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
