<?php

namespace Zareismail\Gutenberg;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider; 
use Laravel\Nova\Nova;
use Laravel\Nova\Observable;
use Zareismail\Cypress\Cypress;

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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/gutenberg.php', 'gutenberg'); 

        Nova::serving(function () {
            $this->servingNova();
        });

        Cypress::discover(app_path('Gutenberg'));
    }   

    /**
     * Register any Nova serives.
     *  
     * @return void
     */
    protected function servingNova()
    {
        Nova::resources((array) config('gutenberg.resources'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\ComponentCommand::class,
            Console\FragmentCommand::class,
        ]);
    }
}
