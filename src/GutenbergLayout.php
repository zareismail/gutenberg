<?php

namespace Zareismail\Gutenberg;

use Illuminate\Http\Request;
use Zareismail\Cypress\Layout;  
use Zareismail\Gutenberg\Gutenberg;

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
        $layout = $this->layout($request);

        return $layout->resolveWidgets($request);
    }

    /**
     * Get the layout for the given request.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Datbase\Eloquent\Model
     */
    public function layout(Request $request)
    {
        return $request->isFragmentRequest() 
                    ? $this->resolveFragmentLayout($request)
                    : $this->resolveComponentLayout($request);
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
            abort_if(is_null($layout), 422, 'Not found any layout to display fragment');
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
            abort_if(is_null($layout), 422, 'Not found any layout to display component');
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
        $layout = $request->isComponentRequest() 
            ? $this->resolveComponentLayout($request) 
            : $this->resolveFragmentLayout($request);

        return $layout->plugins->gutenbergPlugins()->all();
    } 
}
