<?php

namespace Zareismail\Gutenberg; 

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Zareismail\Cypress\Cypress;
use Zareismail\Cypress\Makeable;
use Zareismail\Gutenberg\Compilers\Compiler;

abstract class Template extends Fluent implements Renderable
{      
    use Makeable; 

    /**
     * The logical group associated with the template.
     *
     * @var string
     */
    public static $group = 'Other';

    /**
     * The template html string.
     * 
     * @var string
     */
    protected $html = '';

    /**
     * List of default compilers.
     * 
     * @var array
     */
    protected $compilers = [
        Compilers\CompilesEach::class,
        Compilers\CompilesConditionals::class,
        Compilers\CompilesIs::class,
        Compilers\CompilesTranslations::class,
        Compilers\CompilesVariables::class,
    ];

    /**
     * List of custom compilers.
     * 
     * @var array
     */
    protected static $customCompilers = [ 
    ];

    /**
     * Set the attributes.
     * 
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Register the given variables.
     * 
     * @return array
     */
    abstract public static function variables(): array;

    /**
     * Get the displayable label of the template.
     *
     * @return string
     */
    public static function label()
    {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return static::$group;
    }

    /**
     * Set the template html string.
     * 
     * @param  string $html 
     * @return $this       
     */
    public function withHtml(string $html='')
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get the template html string.
     * 
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {        
        if (app()->hasDebugModeEnabled()) {
            return $this->runCompilers($this->getHtml(), $this->jsonSerialize());
        }
        
        return Cache::sear($this->cacheKey(), function() {
            return $this->runCompilers($this->getHtml(), $this->jsonSerialize());
        });
    }

    /**
     * Get the rendering cachekey.
     * 
     * @return string
     */
    public function cacheKey(): string
    {
        return md5($this->toJson().$this->getHtml());
    }

    /**
     * Run the available compilesr on the given string.
     * 
     * @param  string $expression
     * @param  array  $attributes
     * @return string            
     */
    public function runCompilers(string $expression, array $attributes = [])
    {
        return collect($this->compilers())->reduce(function($expression, $compiler) use ($attributes) {
            return $compiler->compile($expression, $attributes);
        }, $expression);
    }

    /**
     * Get the compiler callbacks.
     * 
     * @return array
     */
    protected function compilers()
    {
        return collect($this->compilers)->map(function($compiler) {
                    return $compiler::make($this);
                })
                ->merge(static::$customCompilers) 
                ->all();
    } 

    /**
     * Register custom compiler.
     * 
     * @param  \Zareismail\Gutenberg\Compilers\Compiler $compiler 
     * @return string           
     */
    public static function extends(Compiler $compiler)
    {
        static::$customCompilers[] = $compiler;

        return new static;
    }
}
