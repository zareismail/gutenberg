<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutenbergWebsite extends Model
{
    use HasFactory;
    use InteractsWithComponents;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'collection',
    ];

    public function uriKey()
    {
        return $this->directory;
    }
}
