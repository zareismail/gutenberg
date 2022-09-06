<?php

namespace Zareismail\Gutenberg\Compilers;

use Zareismail\Cypress\Makeable;

class CompilesIs implements Compiler
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
        $pattern = "/\{\%\s*is\s+(?<first>[^}]+)\s*,\s*(?<second>[^}]+)\s*\%\}(?<expression>(\n*(?!\{\%)[\S\s])+)\{\%\s*endis\s*\%\}/";

        return preg_replace_callback($pattern, function ($matches) use ($attributes) {
            $first = data_get($attributes, trim($matches['first']), $matches['first']);
            $second = data_get($attributes, trim($matches['second']), $matches['second']);

            if (is_numeric($first) && is_numeric($second) && floatval($first) === floatval($second)) {
                return $matches['expression'];
            }

            return strval($first) === strval($second) ? $matches('expression') : '';
        }, $expression);
    }
}
