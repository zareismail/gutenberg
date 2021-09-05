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

    /**
     * Get the first layout available for render.
     * 
     * @return \Illuminate\Database\Eloqeunt\Model
     */
    public function layout()
    {
        return $this->layouts->filter->isActive()->first(function($layout) {
            return true;
        });
    }
}
