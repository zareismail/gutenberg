<?php

namespace Zareismail\Gutenberg\Compilers;

use Zareismail\Cypress\Makeable;

class CompilesTranslations implements Compiler
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
        $pattern = "/\{\{\s*_\((?<key>(?:\n*.)+)\)\s*\}\}/";

        return preg_replace_callback($pattern, function ($matches) {
            return __($matches['key']);
        }, $expression);
    }
}
