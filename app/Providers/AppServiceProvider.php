<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		
        $proxy_url    = getenv('PROXY_URL');
		//dd($proxy_url);
		if (!empty($proxy_url)) {
		   URL::forceRootUrl($proxy_url);
		   $this->app['url']->forceScheme('https');
		}
		
        if (env('APP_ENV') === 'production') {
            $this->app['url']->forceScheme('https');
        }
    }
}
