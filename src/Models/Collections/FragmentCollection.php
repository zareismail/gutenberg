<?php

namespace Zareismail\Gutenberg\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class FragmentCollection extends Collection
{
    /**
     * Filter items for the given handlres.
     *
     * @param  string|array  $handler
     * @return static
     */
    public function forHandler($handler)
    {
        $handlers = is_array($handler) ? $handler : func_get_args();

        return $this->filter(function ($item) use ($handlers) {
            return in_array($item->fragment, $handlers);
        });
    }
}
