<?php

namespace Zareismail\Gutenberg;

trait InteractsWithWebsite
{
    /**
     * Resolve the resoruce's value for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @return void
     */
    public function resolve($request): bool
    {
        app()->setLocale(static::website()->locale);

        return is_callable([parent::class, 'resolve']) ? parent::resolve($request) : true;
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return static::website()->uriKey();
    }

    /**
     * Determine if the component is a fallback component.
     *
     * @return bool
     */
    public static function fallback(): bool
    {
        return static::website()->isFallback();
    }

    /**
     * Get the component fragments.
     *
     * @return string
     */
    public function fragments(): array
    {
        return static::website()
                    ->fragments->map->cypressFragment()
                    ->merge(parent::fragments())
                    ->toArray();
    }

    /**
     * Get  the component coresponding website.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function website()
    {
        return once(function () {
            return Gutenberg::cachedWebsites()->first(function ($website) {
                return $website->cypressComponent() === static::class;
            });
        });
    }

    /**
     * Get the layout instance.
     *
     * @return string
     */
    public function resolveLayout()
    {
        return GutenbergLayout::make();
    }
}
