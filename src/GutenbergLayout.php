<?php

namespace Zareismail\Gutenberg;

use Illuminate\Http\Request;
use Zareismail\Cypress\Layout;  

class GutenbergLayout extends Layout
{        
    /**
     * Get the viewName name for the layout.
     *
     * @return string
     */
    public function viewName(): string
    {
        return 'gutenberg::layout';
    }

    /**
     * Get the widgets available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function widgets(Request $request)
    { 
        $layout = $request->isFragmentRequest() 
                        ? $this->resolveFragmentLayout($request) 
                        : $this->resolveComponentLayout($request); 

        return $layout->resolveWidgets($request);
    } 

    /**
     * Get the layout from fragment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function resolveFragmentLayout(Request $request)
    {
        return tap($request->resolveFragment()->fragment()->layout($request), function($layout) {
            abort_if(is_null($layout), 500);
        }); 
    } 

    /**
     * Get the layout from website.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function resolveComponentLayout(Request $request)
    {
        return tap($request->resolveComponent()->website()->layout($request), function($layout) {
            abort_if(is_null($layout), 500);
        });   
    }

    /**
     * Get the plugins available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function plugins(Request $request)
    {
        return [];
    } 
}
