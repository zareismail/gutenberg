<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class GutenbergFragment extends Model
{
    use Activable; 
    use Fallback; 
    use HasFactory; 
    use HasHandler; 
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
     * Get the table qualified template name.
     *
     * @return string
     */
    public function getQualifiedHandlerName()
    {
        return $this->qualifyColumn('fragment');
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
        if (! $this->isFallback()) {
            $uri = $this->uriKey().'/'.trim($uri, '/');
        }

        return $this->website->getUrl($uri);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\FragmentCollection($models);
    }
}
