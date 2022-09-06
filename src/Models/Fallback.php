<?php

namespace Zareismail\Gutenberg\Models;

trait Fallback
{
    /**
     * Determine if the value of the model's "fallback" attribute is true.
     *
     * @return bool
     */
    public function isFallback()
    {
        return boolval($this->{$this->getFallbackKeyName()});
    }

    /**
     * Query the model's where`fallback` is true.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFallback($query)
    {
        return $this->where($this->getQualifiedFallback(), true);
    }

    /**
     * Get the value of the "fallback" mark.
     *
     * @return string
     */
    public function getQualifiedFallback()
    {
        return $this->qualifyColumn($this->getFallbackKeyName());
    }

    /**
     * Get the value of the "fallback" mark.
     *
     * @return string
     */
    public function getFallbackKeyName()
    {
        return $this->fallbackKey ?? 'fallback';
    }
}
