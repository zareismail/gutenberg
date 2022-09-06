<?php

namespace Zareismail\Gutenberg\Compilers;

use Zareismail\Cypress\Makeable;

class CompilesConditionals implements Compiler
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
        return $this->compileIf($this->compileUnless($expression, $attributes), $attributes);
    }

    /**
     * Replace attributes in the if statements.
     *
     * @param  string  $expression
     * @param  array  $attributes
     * @return string
     */
    protected function compileIf(string $expression, array $attributes = [])
    {
        $pattern = $this->getPattern('if');

        $expression = preg_replace_callback($pattern, function ($matches) use ($attributes) {
            if (! $this->parseCondition($matches['condition'], $attributes)) {
                return '';
            }

            return $matches['expression'];
        }, $expression);

        return preg_match($pattern, $expression)
            ? $this->compileIf($expression, $attributes)
            : $expression;
    }

    /**
     * Replace attributes in the unless statements.
     *
     * @param  string  $expression
     * @param  array  $attributes
     * @return string
     */
    protected function compileUnless(string $expression, array $attributes = [])
    {
        $pattern = $this->getPattern('unless');

        $expression = preg_replace_callback($pattern, function ($matches) use ($attributes) {
            if ($this->parseCondition($matches['condition'], $attributes)) {
                return '';
            }

            return $matches['expression'];
        }, $expression);

        return preg_match($pattern, $expression)
            ? $this->compileUnless($expression, $attributes)
            : $expression;
    }

    /**
     * Get pattern for given condition.
     *
     * @param  string  $condition
     * @return string
     */
    protected function getPattern(string $condition)
    {
        return "/\{\%\s*{$condition}\s+(?<condition>[^}]+)\%\}(?<expression>(\n*(?!\{\%)[\S\s])+)\{\%\s*end{$condition}\s*\%\}/";
    }

    /**
     * Parse the given operand.
     *
     * @param  string  $condition
     * @param  array  $attributes
     * @return mixed
     */
    protected function parseCondition(string $condition, array $attributes = [])
    {
        $condition = trim($condition);

        return data_get($attributes, $condition);
    }
}
