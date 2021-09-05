<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zareismail\Markable\Markable;

class GutenbergLayout extends Model
{
    use Activable; 
    use HasFactory; 
    use Markable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [ 
    ];

    /**
     * Query the realted GutenbergWebsite.
     * 
     * @return [type] [description]
     */
    public function websites()
    {
        return $this->belongsToMany(GutenbergWebsite::class, 'gutenberg_layout_website');
    }

    /**
     * Query the realted GutenbergFragment.
     * 
     * @return [type] [description]
     */
    public function fragments()
    {
        return $this->belongsToMany(GutenbergFragment::class, 'gutenberg_fragment_layout');
    }

    /**
     * Get the `uriKey` of corresponding fragment.
     * 
     * @return string
     */
    public function uriKey()
    {
        return md5(static::class.$this->getKey());
    }
}
