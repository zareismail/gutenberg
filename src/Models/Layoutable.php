<?php

namespace Zareismail\Gutenberg\Models;

use Zareismail\Gutenberg\Nova\Layout;

trait Layoutable
{
    /**
     * Query the rlated GutenbergLayout.
     *
     * \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function layout()
    {
        return $this->belongsTo(config('gutenberg.models.'.Layout::class), 'gutenberg_layout_id');
    }
}
