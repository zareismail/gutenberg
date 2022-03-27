<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutenbergWebsite extends Model
{
    use Activable; 
    use Fallback; 
    use HasFactory; 
    use HasHandler; 
    use InteractsWithComponents;
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
     * Query the realted GutenbergFragment.
     * 
     * @return [type] [description]
     */
    public function fragments()
    {
        return $this->hasMany(GutenbergFragment::class, 'website_id');
    } 

    /**
     * Get the table qualified template name.
     *
     * @return string
     */
    public function getQualifiedHandlerName()
    {
        return $this->qualifyColumn('component');
    }  

    /**
     * Get the `uriKey` of corresponding component.
     * 
     * @return string
     */
    public function uriKey()
    {
        return trim($this->directory, '/');
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

        return url($uri);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\WebsiteCollection($models);
    }
}
