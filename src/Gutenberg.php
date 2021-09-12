<?php

namespace Zareismail\Gutenberg; 

use Illuminate\Support\Collection;
use Zareismail\Cypress\Cypress;

class Gutenberg extends Cypress
{   
    /**
     * The registered component names.
     *
     * @var array
     */
    public static $components = [];

    /**
     * The registered fragment names.
     *
     * @var array
     */
    public static $fragments = [];

    /**
     * The registered widget names.
     *
     * @var array
     */
    public static $widgets = [];

    /**
     * The registered template names.
     *
     * @var array
     */
    public static $templates = [];

    /**
     * Register the given fragments.
     *
     * @param  array  $fragments
     * @return static
     */
    public static function fragments(array $fragments)
    {
        static::$fragments = array_unique(
            array_merge(static::$fragments, $fragments)
        );

        return new static;
    } 

    /**
     * Return the base collection of Cypress fragments.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function fragmentCollection()
    {
        return Collection::make(static::$fragments);
    } 

    /**
     * Register the given widgets.
     *
     * @param  array  $widgets
     * @return static
     */
    public static function widgets(array $widgets)
    {
        static::$widgets = array_unique(
            array_merge(static::$widgets, $widgets)
        );

        return new static;
    } 

    /**
     * Return the base collection of Cypress widgets.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function widgetCollection()
    {
        return Collection::make(static::$widgets);
    } 

    /**
     * Register the given templates.
     *
     * @param  array  $templates
     * @return static
     */
    public static function templates(array $templates)
    {
        static::$templates = array_unique(
            array_merge(static::$templates, $templates)
        );

        return new static;
    } 

    /**
     * Return the base collection of Cypress templates.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function templateCollection()
    {
        return Collection::make(static::$templates);
    } 

    /**
     * Get the cache websites.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function cachedWebsites()
    {
        return once(function() {
            $resource = config('gutenberg.resources.website');

            return $resource::newModel()->with('fragments')->get();
        });
    }

    /**
     * Get the cache fragments.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function cachedFragments()
    {
        return once(function() {
            $resource = config('gutenberg.resources.fragment');

            return $resource::newModel()->get();
        });
    }

    /**
     * Get the cache widgets.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function cachedWidgets()
    {
        return once(function() {
            $resource = config('gutenberg.resources.widget');

            return $resource::newModel()->get();
        });
    }

    /**
     * Get the cache templates.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function cachedTemplates()
    {
        return once(function() {
            $resource = config('gutenberg.resources.template');

            return $resource::newModel()->get();
        });
    }
}
