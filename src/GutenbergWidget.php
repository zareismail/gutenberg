<?php

namespace Zareismail\Gutenberg;

use Zareismail\Cypress\Widget;  
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Models\GutenbergWidget as Model;

class GutenbergWidget extends Widget
{       
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
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return void                  
     */
    public function boot(CypressRequest $request, $layout)
    {   
        call_user_func($this->bootstrapCallback, $request, $this, $layout);
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
        return $this->resolveForDisplay($this->serializeForDisplay());
    }

    /**
     * Resolve the widget for display.
     *
     * @param  mixed  $resource
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
        return [];
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
