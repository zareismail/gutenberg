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
        return [];
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
