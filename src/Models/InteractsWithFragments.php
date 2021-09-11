<?php

namespace Zareismail\Gutenberg\Models;
 
use Illuminate\Support\Facades\Artisan;

trait InteractsWithFragments 
{
    /**
     * Bootstrap any trait resources.
     * 
     * @return void
     */
    public static function bootInteractsWithFragments()
    { 
        static::saved(function($model) {
            $model->ensureFragmentExists();
        }); 
    }
    
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

    /**
     * Query where has the given fragments.
     * 
     * @param  \Illuminate\Database\Eloqeunt\Builder $query     
     * @param  array  $fragments 
     * @return \Illuminate\Database\Eloqeunt\Builder            
     */
    public function scopeFragments($query, array $fragments)
    {
        return $query->whereIn($this->getQualifiedFragmentName(), $fragments);
    }
 
    /**
     * Get the table qualified uri name.
     *
     * @return string
     */
    public function getQualifiedFragmentName()
    {
        return $this->qualifyColumn($this->getFragmentName());
    }

    /**
     * Get the uri for the model.
     *
     * @return string
     */
    public function getFragmentName()
    {
        return 'fragment';
    }
}
