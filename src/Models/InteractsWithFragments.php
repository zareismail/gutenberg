<?php

namespace Zareismail\Gutenberg\Models;
 
use Illuminate\Support\Facades\Artisan;

trait InteractsWithFragments 
{
    /**
     * Ensure that corresponding Cypress fragment exists.
     * 
     * @return void
     */
    public function ensureFragmentExists()
    { 
        $this->hasCypressFragment() || $this->createCypressFragment();
    }

    /**
     * Determine if corresponding Cypress fragment file exists.
     * 
     * @return bool
     */
    public function hasCypressFragment()
    { 
        return class_exists($this->cypressFragment());
    } 

    /**
     * Get corresponding Cypress fragment.
     * 
     * @return string
     */
    public function cypressFragment()
    {
        return app()->getNamespace().'Gutenberg\\Fragments\\'.$this->cypressFragmentName();
    }

    /**
     * Get Cypress fragment name.
     * 
     * @return string
     */
    public function cypressFragmentName()
    {
        return 'GutenbergFragment'.$this->getKey();
    }

    /**
     * Create corresponding Cypress fragment.
     * 
     * @return void
     */
    public function createCypressFragment()
    {
        Artisan::call('gutenberg:fragment', [
            'name' => $this->cypressFragmentName(),
            '--fragment' => $this->fragment,
        ]);
    }
}
