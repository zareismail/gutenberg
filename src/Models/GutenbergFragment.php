<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutenbergFragment extends Model
{
    use HasFactory; 

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'collection',
    ];

    /**
     * Query the realted GutenbergWebsite.
     * 
     * @return [type] [description]
     */
    public function website()
    {
        return $this->belongsTo(GutenbergWebsite::class);
    }

    /**
     * Get the `uriKey` of corresponding fragment.
     * 
     * @return string
     */
    public function uriKey()
    {
        return $this->prefix;
    }
}
