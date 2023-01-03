<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LayoutWidget extends Pivot
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order' => 'integer',
        'config' => 'collection',
    ];
}
