<?php

namespace Zareismail\Gutenberg\Models;
 
use Illuminate\Support\Facades\Artisan;

trait InteractsWithComponents 
{
    /**
     * Ensure that corresponding Cypress component exists.
     * 
     * @return void
     */
    public function ensureComponentExists()
    { 
        $this->hasCypressComponent() || $this->createCypressComponent();
    }

    /**
     * Determine if corresponding Cypress component file exists.
     * 
     * @return bool
     */
    public function hasCypressComponent()
    { 
        return class_exists($this->cypressComponent());
    } 

    /**
     * Get corresponding Cypress component.
     * 
     * @return string
     */
    public function cypressComponent()
    {
        return app()->getNamespace().'Gutenberg\\'.$this->cypressComponentName();
    }

    /**
     * Get Cypress component name.
     * 
     * @return string
     */
    public function cypressComponentName()
    {
        return 'GutenbergComponent'.$this->getKey();
    }

    /**
     * Create corresponding Cypress component.
     * 
     * @return void
     */
    public function createCypressComponent()
    {
        Artisan::call('gutenberg:component', [
            'name' => $this->cypressComponentName(),
            '--component' => $this->component,
        ]);
    }
}
