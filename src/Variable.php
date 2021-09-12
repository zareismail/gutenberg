<?php

namespace Zareismail\Gutenberg; 
 
use Zareismail\Cypress\Makeable;

class Variable 
{      
    use Makeable;

    /**
     * The variabel attribute name.
     * 
     * @var string
     */
    public $attribute;

    /**
     * The variabel attribute help.
     * 
     * @var string
     */
    public $help;

    public function __construct(string $attribute, string $help)
    {
        $this->attribute = $attribute;
        $this->help = $help;
    }

    /**
     * Get the displayable name of the variable.
     *
     * @return string
     */
    public function name()
    {
        return $this->attribute;
    }

    /**
     * Get the displayable help of the variable.
     *
     * @return string
     */
    public function help()
    {
        return $this->help;
    } 
}
