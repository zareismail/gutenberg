<?php

namespace Zareismail\Gutenberg;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelServiceProvider;   
use Laravel\Nova\Nova;
use Laravel\Nova\Observable;
use Zareismail\Cypress\Cypress;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\GutenbergFragment::class => Policies\Fragment::class,
        Models\GutenbergLayout::class   => Policies\Layout::class,
        Models\GutenbergPlugin::class   => Policies\Plugin::class,
        Models\GutenbergTemplate::class => Policies\Template::class,
        Models\GutenbergWebsite::class  => Policies\Website::class,
        Models\GutenbergWidget::class   => Policies\Widget::class,
    ];

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
        $this->registerPolicies();

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
        
        collect(config('gutenberg.models'))->each(function($model, $resource) {
            Nova::$resourcesByModel[$model] = $resource;
        });
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
            Console\TemplateCommand::class,
        ]);
    }
}
