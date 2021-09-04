<?php

namespace Zareismail\Gutenberg\Console;

use Zareismail\Cypress\Console\ComponentCommand as Command;
use Symfony\Component\Console\Input\InputOption;

class ComponentCommand extends Command
{ 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gutenberg:component'; 

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $component = $this->option('component'); 

        return str_replace('{{ component }}', $component, parent::buildClass($name));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Gutenberg';
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.str_replace('cypress/', '', $stub);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    { 
        return array_merge(parent::getOptions(), [
            ['component', 'c', InputOption::VALUE_REQUIRED, 'The component class being extended.'],
        ]);
    }
}
