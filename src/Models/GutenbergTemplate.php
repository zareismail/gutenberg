<?php

namespace Zareismail\Gutenberg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GutenbergTemplate extends Model
{
    use HasFactory;
    use HasHandler;
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function plugins()
    {
        return $this->belongsToMany(GutenbergPlugin::class, 'gutenberg_plugin_template')
            ->withPivot('order');
    }

    /**
     * Query where has the given template handlers.
     *
     * @param  \Illuminate\Database\Elqoeunt\Builder  $query
     * @param  string|array  $templates
     * @return \Illuminate\Database\Elqoeunt\Builder
     */
    public function scopeTemplates($query, $templates)
    {
        return $query->handledBy((array) $templates);
    }

    /**
     * Get the table qualified template name.
     *
     * @return string
     */
    public function getQualifiedHandlerName()
    {
        return $this->qualifyColumn('template');
    }

    /**
     * Get the `uriKey` of corresponding fragment.
     *
     * @return string
     */
    public function uriKey()
    {
        return md5(static::class . $this->getKey());
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
     * @return bool
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

        return tap($template::make($attributes), function ($template) {
            $template->withHtml(strval($this->html));
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
        return new Collections\TemplateCollection($models);
    }
}
