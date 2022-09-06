<?php

namespace Zareismail\Gutenberg\Compilers;

use Zareismail\Cypress\Makeable;

class CompilesIn implements Compiler
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
        $pattern = "/\{\%\s*in\s+(?<value>[^},]+)\s*,\s*(?<array>[^}]+)\s*\%\}(?<expression>(\n*(?!\{\%)[\S\s])+)\{\%\s*endin\s*\%\}/";

        return preg_replace_callback($pattern, function ($matches) use ($attributes) {
            $value = data_get($attributes, trim($matches['value']), $matches['value']);
            $array = (array) data_get(
                $attributes,
                trim($matches['array']),
                explode(',', trim($matches['array']))
            );

            return collect($array)->contains($value) ? $matches['expression'] : '';
        }, $expression);
    }
}
