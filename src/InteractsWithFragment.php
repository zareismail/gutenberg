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
