<?php

namespace Zareismail\Gutenberg; 

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
}
