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
     * @return \Zareismail\Cypress\ComponentCollection
     */
    public static function fragmentCollection()
    {
        return Collection::make(static::$fragments);
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

            return $resource::newModel()->get();
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
}
