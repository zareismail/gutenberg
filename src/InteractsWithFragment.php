<?php

namespace Zareismail\Gutenberg; 

use Zareismail\Gutenberg\Gutenberg; 

trait InteractsWithFragment
{        
    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return static::fragment()->uriKey();
    } 
     
    /**
     * Determine if the fragment is the fallback.
     *
     * @return boolean
     */
    public static function fallback(): bool
    { 
        return empty(trim(static::uriKey(), '/'));
    }

    /**
     * Get  the component coresponding fragment.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function fragment()
    {
        return once(function() {
            return Gutenberg::cachedFragments()->first(function($fragment) {
                return $fragment->cypressFragmentName() === class_basename(static::class);
            });
        });
    }  
}
