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
     * Query the realted GutenbergTemplate.
     * 
     * @return [type] [description]
     */
    public function template()
    {
        return $this->belongsTo(GutenbergTemplate::class);
    } 

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
     * Determine if the corresponding CypressWidget exists.
     * 
     * @return boolean
     */
    public function hasCypressWidget()
    {
        return class_exists($this->widget);
    }

    /**
     * Get the corresponding CypressWidget.
     * 
     * @return \Zareismail\Cypress\Widget
     */
    public function cypressWidget()
    {
        $widget = $this->widget;

        return tap($widget::make($this->uriKey()), function($widget) {
            $widget->withMeta(collect($this->config)->toArray());
            $widget->bootstrapUsing(function($request, $widget, $layout) { 
                abort_unless(
                    $this->template, 
                    422, 
                    "Not found template to display widget: {$this->name}"
                );

                $this->template->plugins
                     ->filter->isActive()
                     ->flatMap->gutenbergPlugins()
                     ->each->boot($request, $layout);
                     
                $widget->displayUsing(function($attributes) { 
                    return $this->template->gutenbergTemplate($attributes)->render(); 
                });
            });
        });
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\WidgetCollection($models);
    }
}
