<?php

namespace Zareismail\Gutenberg\Models;

trait HasHandler
{
    /**
     * Query where has the given template handlers.
     *
     * @param  \Illuminate\Database\Elqoeunt\Builder  $query
     * @param  string|array  $templates
     * @return \Illuminate\Database\Elqoeunt\Builder
     */
    public function scopeHandledBy($query, $handlers)
    {
        return $query->whereIn($this->getQualifiedHandlerName(), (array) $handlers);
    }

    /**
     * Get the table qualified handler name.
     *
     * @return string
     */
    public function getQualifiedHandlerName()
    {
        return $this->qualifyColumn('handler');
    }
}
