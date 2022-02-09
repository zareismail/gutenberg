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
    	$pattern = "/\{\{\s*(?<variable>[0-9a-zA-Z-_.]+)(?:\s*or\s*(?<default>[^}]+))?\s*\}\}/"; 

        return preg_replace_callback($pattern, function($matches) use ($attributes) {   
            return strval(data_get(
                $attributes, $matches['variable'], trim($matches['default'] ?? '')
            ));  
        }, $expression); 
    }
}