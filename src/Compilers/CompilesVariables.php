<?php 

namespace Zareismail\Gutenberg\Compilers;

use Zareismail\Cypress\Makeable;

class CompilesVariables implements Compiler
{ 
	use Makeable;

    /**
     * Replace attributes in the given expression.
     *
     * @param  string  $expression
     * @param  array  $attributes
     * @return string
     */
    public function compile(string $expression, array $attributes = [])
    {
    	$pattern = "/\{\{\s*(?<variable>[0-9a-zA-Z-_.]+)(?:\s+or\s+(?<default>[^}]+))?\s*\}\}/"; 

        return preg_replace_callback($pattern, function($matches) use ($attributes) {   
            $defaultValue = isset($matches['default'])  
                ? $this->getDefaultValue($attributes, trim($matches['default']))
                : null;

            return strval(data_get(
                $attributes, $matches['variable'], $defaultValue
            ));  
        }, $expression); 
    }

    /**
     * get value of defaults.
     * 
     * @param  array $attributes 
     * @param  string $defaultKey 
     * @return mixed             
     */
    public function getDefaultValue($attributes, $defaultKey)
    {
        $defaults = $this->parseDefaults($defaultKey);
        $defaultKey = $defaults->first(function($default) use ($attributes) {
            return data_get($attributes, $default);
        }, $defaults->pop());

        if ($defaultKey === 'null' || 
            $defaultKey === "''" || 
            $defaultKey === '""'
            ) {
            return null;
        }

        return data_get($attributes, $defaultKey, $defaultKey);         
    }

    /**
     * Parse default key string.
     * 
     * @param  string $defaultKey 
     * @return array             
     */
    protected function parseDefaults($defaultKey)
    {
        $separator = md5($defaultKey);  
        $defaultKey = preg_replace('/\s+or\s+/', $separator, $defaultKey);
        $defaults = (array) explode($separator, $defaultKey); 

        return collect($defaults)->map(function($default) {
            return trim($default);
        });
    }
}