<?php

namespace Zareismail\Gutenberg\Models; 

trait Layoutable 
{     
    /**
     * Query the rlated GutenbergLayout.
     * 
     * \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Layouts()
    {
        return $this->morphToMany(GutenbergLayout::class, 'layoutable', 'gutenberg_layoutable');
    }
}
