<?php

namespace Zareismail\Gutenberg;

trait InteractsWithCache
{
    /**
     * The cache key value.
     *
     * @var string
     */
    private $cacheKey;

    /**
     * The cache time.
     *
     * @var string
     */
    private $ttl = 0;

    /**
     * Get result from cache.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @return void
     */
    public function sear($callback)
    {
        if (! $this instanceof Cacheable || ! $this->cacheTime()) {
            return call_user_func($callback);
        }

        return \Cache::remember($this->cacheKey(), $this->cacheTime(), $callback);
    }

    /**
     * Get cache key.
     *
     * @return string
     */
    public function cacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Get cache time.
     *
     * @return string
     */
    public function cacheTime()
    {
        return \Laravel\Nova\Nova::check(request()) ? 0 : $this->ttl;
    }

    /**
     * Set cache key.
     *
     * @param  string  $cacheKey
     * @return $this
     */
    public function withCacheKey(string $cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * Set cache time.
     *
     * @param  string  $ttl
     * @return $this
     */
    public function withCacheTime(int $ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Forget cache value.
     *
     * @return $this
     */
    public function forget()
    {
        \Cache::forget($this->cacheKey());

        return $this;
    }
}
