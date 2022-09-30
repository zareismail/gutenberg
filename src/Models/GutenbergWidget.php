<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zareismail\Gutenberg\Cacheable;

class GutenbergWidget extends Model
{
    use Activable;
    use HasFactory;
    use HasHandler;

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($model) {
            $model->cypressWidget()->forget();
        });
        static::deleting(function ($model) {
            $model->cypressWidget()->forget();
        });
    }

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
        return $this->belongsTo(GutenbergTemplate::class, 'gutenberg_template_id');
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
     * Get the table qualified template name.
     *
     * @return string
     */
    public function getQualifiedHandlerName()
    {
        return $this->qualifyColumn('widget');
    }

    /**
     * Get the `uriKey` of corresponding widget.
     *
     * @return string
     */
    public function uriKey()
    {
        return md5(static::class . $this->getKey());
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
     * Determin if widget is cachable.
     *
     * @return bool
     */
    public function isCacheable()
    {
        return $this->isAvailable() && $this->cypressWidget() instanceof Cacheable;
    }

    /**
     * Determine if the module available for render.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->isActive() && $this->hasCypressWidget();
    }

    /**
     * Determine if the corresponding CypressWidget exists.
     *
     * @return bool
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

        return tap($widget::make($this->uriKey()), function ($widget) {
            $widget->withMeta(collect($this->config)->toArray());
            $widget->withCacheKey($this->uriKey());
            $widget->withCacheTime(intval($this->ttl));
            $widget->bootstrapUsing(function ($request, $widget, $layout) {
                abort_unless(
                    $this->template,
                    422,
                    "Not found template to display widget: {$this->name}"
                );

                $this->template->plugins->boot($request, $layout);

                $widget->displayUsing(function ($attributes) {
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
