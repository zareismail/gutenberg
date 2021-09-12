<?php

namespace Zareismail\Gutenberg; 

trait HasTemplate  
{    
    /**
     * Render a template for given data.
     * 
     * @param  string $templateId
     * @param  array  $data      
     * @return string            
     */
    public function renderTemplate($templateId, array $data = [])
    { 
        return $this->template($templateId)
                    ->gutenbergTemplate($data)
                    ->render();
    }

    /**
     * Get the tempalte for the given key.
     * 
     * @param integer  $key 
     * @return \Zareismail\Gutenberg\Models\GutenbergTemplate           
     */
    public function template($key)
    {
        return tap(Gutenberg::cachedTemplates()->find($key), function($template) {
            abort_if(is_null($template), 422, 'Not found template to display widget');
        });
    }
}
