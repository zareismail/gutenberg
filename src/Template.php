<?php

namespace Zareismail\Gutenberg; 

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Zareismail\Cypress\Cypress;
use Zareismail\Cypress\Makeable;

abstract class Template extends Fluent implements Renderable
{      
    use Makeable;

    /**
     * The template html string.
     * 
     * @var string
     */
    protected $html = '';

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
        $html = str_replace('/@@/', '__PCALEHOLDER__', $this->getHtml());

        $string = preg_replace_callback($this->getPatterns(), function($matches) {
            list($string, $method, $key) = $matches; 
            $default = $matches[3] ?? null;  

            return method_exists($this, $method) 
                ? $this->{$method}($key, $default) 
                : $string; 
        }, $html);

        return str_replace('__PCALEHOLDER__', '@@', $string);
    }

    /**
     * Get the regex patterns.
     * 
     * @return array
     */
    private function getPatterns()
    {
        return [
            '/(?<!@)\@(\w+)\(([^@]+),([^@]+)\)/',
            '/(?<!@)\@(\w+)\(([^@]+)\)/',
        ];
    }

    /**
     * Get the value for the given key.
     * 
     * @param  string $key     
     * @param  mixed $default 
     * @return mixed          
     */
    protected function value($key, $default = null)
    {
        return data_get($this->getAttributes(), $key, $default);
    } 

    /**
     * Get the transaltion for the given key.
     * 
     * @param  string $key      
     * @return string          
     */
    protected function trans($key)
    {
        return __($key);
    } 
}
