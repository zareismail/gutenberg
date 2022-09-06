<?php

namespace Zareismail\Gutenberg;

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
     * @return bool
     */
    public static function fallback(): bool
    {
        return static::fragment()->isFallback();
    }

    /**
     * Get  the component coresponding fragment.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function fragment()
    {
        return once(function () {
            return Gutenberg::cachedFragments()->first(function ($fragment) {
                return $fragment->cypressFragmentName() === class_basename(static::class);
            });
        });
    }
}
