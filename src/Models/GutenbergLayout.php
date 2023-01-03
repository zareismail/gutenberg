<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutenbergLayout extends Model
{
    use Activable;
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Query the realted GutenbergWebsite.
     *
     * @return [type] [description]
     */
    public function websites()
    {
        return $this->hasMany(GutenbergWebsite::class);
    }

    /**
     * Query the realted GutenbergFragment.
     *
     * @return [type] [description]
     */
    public function fragments()
    {
        return $this->morphedByMany(GutenbergFragment::class);
    }

    /**
     * Query the realted GutenbergPlugin.
     *
     * @return \Illuminate\Database\Eloquent\Rleations\BelongsToMany
     */
    public function plugins()
    {
        return $this->belongsToMany(GutenbergPlugin::class, 'gutenberg_layout_plugin')
            ->withPivot('order')
            ->with('plugins')
            ->orderBy('order');
    }

    /**
     * Query the realted GutenbergFragment.
     *
     * @return [type] [description]
     */
    public function widgets()
    {
        return $this->belongsToMany(GutenbergWidget::class, 'gutenberg_layout_widget')
            ->withPivot('order', 'config')
            ->orderBy('order')
            ->using(LayoutWidget::class);
    }

    /**
     * Get the availabel widgets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function resolveWidgets($request)
    {
        return $this->widgets->loadMissing('template.plugins')
            ->filter->isAvailable()
            ->filter(function ($widget) use ($request) {
                $widgetName = class_basename($widget->widget);
                $callback = function ($filterCallback) use ($request, $widget) {
                    if (method_exists($request->component(), $filterCallback) && ! $request->resolveComponent()->{$filterCallback}($request, $widget)) {
                        return false;
                    }

                    if (! $request->isFragmentRequest() || ! method_exists($request->fragment(), $filterCallback)) {
                        return true;
                    }

                    return  $request->resolveFragment()->{$filterCallback}($request, $widget);
                };

                return collect(['filterRelatableWidget', "filterRelatable{$widgetName}Widget"])->every($callback);
            })
            ->map->cypressWidget()
            ->all();
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
