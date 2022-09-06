<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zareismail\Gutenberg\GutenbergPlugin as Plugin;

class GutenbergPlugin extends Model
{
    use Activable;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'assets' => 'collection',
    ];

    /**
     * Query related GutenbergPlugin.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsToMany
     */
    public function plugins()
    {
        return $this->belongsToMany(
            static::class, 'gutenberg_plugin_dependencuy', 'plugin_id', 'dependency_id'
        )->with('plugins');
    }

    /**
     * Create plugin instances.
     *
     * @return array
     */
    public function gutenbergPlugins()
    {
        $plugins = $this->assets->map->attributes->mapInto(Plugin::class);

        return $this->plugins->toBase()->filter->isActive()->map->gutenbergPlugins()->merge($plugins);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\PluginCollection($models);
    }
}
