<?php

namespace Zareismail\Gutenberg\Cypress\Widgets;

use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Cypress\Widget as CypressWidget;
use Zareismail\Gutenberg\InteractsWithCache;

abstract class Widget extends CypressWidget
{
    use InteractsWithCache;

    /**
     * The logical group associated with the template.
     *
     * @var string
     */
    public static $group = 'Other';

    /**
     * The callback to be used to bootstrap widget.
     *
     * @var \Closure
     */
    public $bootstrapCallback;

    /**
     * The callback to be used to resolve the widget's display string.
     *
     * @var \Closure
     */
    public $displayCallback;

    /**
     * Bootstrap the widget for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @param  \Zareismail\Cypress\Layout  $layout
     * @return void
     */
    public function boot(CypressRequest $request, $layout)
    {
        call_user_func($this->bootstrapCallback, $request, $this, $layout);
    }

    /**
     * Get the logical group associated with the widget.
     *
     * @return string
     */
    public static function group()
    {
        return static::$group;
    }

    /**
     * Define the callback that should be used to bootstrap the widget.
     *
     * @param  callable  $bootstrapCallback
     * @return $this
     */
    public function bootstrapUsing(callable $bootstrapCallback)
    {
        $this->bootstrapCallback = $bootstrapCallback;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->sear(function () {
            return $this->resolveForDisplay($this->serializeForDisplay());
        });
    }

    /**
     * Resolve the widget for display.
     *
     * @param  mixed  $widget
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay($attributes): string
    {
        return call_user_func($this->displayCallback, $this->serializeForDisplay());
    }

    /**
     * Serialize the widget fro display.
     *
     * @return array
     */
    public function serializeForDisplay(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Define the callback that should be used to display the widget.
     *
     * @param  callable  $displayCallback
     * @return $this
     */
    public function displayUsing(callable $displayCallback)
    {
        $this->displayCallback = $displayCallback;

        return $this;
    }
}
