<?php

namespace Zareismail\Gutenberg\Models\Collections;
 
use Illuminate\Database\Eloquent\Collection; 

class WidgetCollection extends Collection
{  
    /**
     * Bootstrap all plugins and dependencies.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return static          
     */
    public function boot($request, $layout)
    {  
        return $this->tap(function() use ($request, $layout)  {
            $this->gutenbergPlugins()->each->boot($request, $layout);
        });
    } 

    /**
     * Create plugin instances.
     *  
     * @return array                  
     */
    public function gutenbergPlugins()
    {  
        return $this->filter->isActive()->map->gutenbergPlugins()->flatten();
    }
}
