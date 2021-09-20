<?php 

namespace Zareismail\Gutenberg\Compilers;

interface Compiler
{ 
    /**
     * Replace attributes in the given expression.
     *
     * @param  string  $expression
     * @param  array  $attributes
     * @return string
     */
    public function compile(string $expression, array $attributes = []);
}