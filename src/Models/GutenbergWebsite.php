<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zareismail\Markable\Markable;

class GutenbergWebsite extends Model
{
    use Activable; 
    use HasFactory; 
    use InteractsWithComponents;
    use Markable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'collection',
    ]; 

    /**
     * Query the realted GutenbergFragment.
     * 
     * @return [type] [description]
     */
    public function fragments()
    {
        return $this->hasMany(GutenbergFragment::class, 'website_id');
    } 

    /**
     * Get the `uriKey` of corresponding component.
     * 
     * @return string
     */
    public function uriKey()
    {
        return $this->directory;
    }
}
