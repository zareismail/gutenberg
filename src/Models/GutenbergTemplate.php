<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class GutenbergTemplate extends Model
{
    use HasFactory;  
    use SoftDeletes; 

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [  
    ]; 

    /**
     * Get the `uriKey` of corresponding fragment.
     * 
     * @return string
     */
    public function uriKey()
    {
        return md5(static::class.$this->getKey());
    } 

    /**
     * Get the GutenbergTemplate variables.
     * 
     * @return array
     */
    public function variables()
    {
        if ($this->hasGutenbergTemplate() && $template = $this->gutenbergTemplate()) {
            return method_exists($template, 'variables') 
                    ? (array) $template::variables() 
                    : [];
        }

        return [];
    }

    /**
     * Determine if the corresponding GutenbergTemplate exists.
     * 
     * @return boolean
     */
    public function hasGutenbergTemplate()
    {
        return class_exists($this->template);
    }

    /**
     * Get the corresponding GutenbergTemplate.
     * 
     * @return \Zareismail\Gutenberg\Template
     */
    public function gutenbergTemplate(array $attributes = [])
    {
        $template = $this->template;

        return tap($template::make($attributes), function($template) {
            $template->withHtml(strval($this->html)); 
        });
    }
}
