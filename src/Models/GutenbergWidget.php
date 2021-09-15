<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class GutenbergWidget extends Model
{
    use Activable; 
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
    public function layouts()
    {
        return $this->belongsToMany(GutenbergLayout::class, 'gutenberg_layout_widget');
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

    /**
     * Get the CypressWidget fields.
     * 
     * @return array
     */
    public function fields($request)
    {
        if ($this->hasCypressWidget() && $widget = $this->cypressWidget()) {
            return method_exists($widget, 'fields') ? (array) $widget::fields($request) : [];
        }

        return [];
    }

    /**
     * Determine if the module available for render.
     * 
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->isActive() && $this->hasCypressWidget();
    }

    /**
     * Determine if the corresponding CypressWdiget exists.
     * 
     * @return boolean
     */
    public function hasCypressWidget()
    {
        return class_exists($this->widget);
    }

    /**
     * Get the corresponding CypressWdiget.
     * 
     * @return \Zareismail\Cypress\Widget
     */
    public function cypressWidget()
    {
        $widget = $this->widget;

        return tap($widget::make($this->uriKey()), function($widget) {
            $widget->withMeta(collect($this->config)->toArray());
        });
    }
}
