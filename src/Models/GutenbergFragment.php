<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class GutenbergFragment extends Model
{
    use Activable; 
    use HasFactory; 
    use InteractsWithFragments; 
    use Layoutable; 
    use Maintainable; 

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
        return trim($this->prefix, '/');
    }

    /**
     * Get the url for given uri.
     * 
     * @param  string $uri 
     * @return string      
     */
    public function getUrl(string $uri = '')
    {
        return $this->website->getUrl($this->uriKey().'/'.trim($uri, '/'));
    }
}
