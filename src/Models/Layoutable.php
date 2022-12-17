<?php

namespace Zareismail\Gutenberg\Models;

trait Layoutable
{
    /**
     * Query the rlated GutenbergLayout.
     *
     * \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function layout()
    {
        return $this->belongsTo(GutenbergLayout::class, 'gutenberg_layout_id');
    }
}
