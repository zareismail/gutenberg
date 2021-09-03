<?php

namespace Zareismail\Gutenberg;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Zareismail\Gutenberg\Http\Middleware\Authorize;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'gutenberg');

        Nova::serving(function (ServingNova $event) {
            //
        });
    }  

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
