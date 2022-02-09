<?php 

namespace Zareismail\Gutenberg\Compilers;

use Illuminate\Support\Str;
use Zareismail\Cypress\Makeable;
use Zareismail\Gutenberg\Template;

class CompilesEach implements Compiler
{ 
    use Makeable;

    /**
     * The tempalte instance.
     * 
     * @var \Zareismail\Gutenberg\Template
     */
    protected $template;

    /**
     * Create new each compiler.
     * 
     * @param \Zareismail\Gutenberg\Template $template 
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    /**
     * Replace attributes in the given expression.
     *
     * @param  string  $expression
     * @param  array  $attributes
     * @return string
     */
    public function compile(string $expression, array $attributes = [])
    { 
        $pattern = $this->getPattern('each');

        return preg_replace_callback($pattern, function($matches) use ($attributes) {
            list($variableName, $valueName, $keyName) = $this->parseArguments($matches['arguments']);
            $items = data_get($attributes, $variableName, []);
            $reduceCallback = function($html, $item, $key) use ($matches, $keyName, $valueName) {
                $variables = array_merge($attributes, [
                    $keyName => $key,
                    $valueName => $item,
                ]);
                
                $compiled = $this->template->runCompilers($matches['expression'], $variables);

                return $html . $compiled;
            };

            return collect($items)->reduce($reduceCallback);
        }, $expression);
    } 

    /**
     * Get pattern for given loop.
     * 
     * @param  string $loop 
     * @return string            
     */
    protected function getPattern(string $loop)
    {
        return "/\{\#\s*{$loop}\s+(?<arguments>[^}]+)\#\}(?<expression>(\n*(?!\{\#)[\S\s])+)\{\#\s*end{$loop}\s*\#\}/";
    }

    /**
     * Parse the given operand.
     * 
     * @param  string $arguments 
     * @param  array $attributes
     * @return mixed            
     */
    protected function parseArguments(string $arguments, array $attributes = [])
    { 
        $parameters = explode(',', $arguments);

        return [
            trim($parameters[0]) ?? $arguments,
            trim($parameters[1]) ?? Str::singular($arguments),
            trim($parameters[2]) ?? 'index',
        ];
    }
}