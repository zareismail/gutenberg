<?php

namespace Zareismail\Gutenberg\Console;

use Symfony\Component\Console\Input\InputOption;
use Zareismail\Cypress\Console\FragmentCommand as Command;

class FragmentCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gutenberg:fragment';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $fragment = $this->option('fragment');

        return str_replace('{{ fragment }}', $fragment, parent::buildClass($name));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Gutenberg\Fragments';
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
            ['fragment', 'c', InputOption::VALUE_REQUIRED, 'The fragment class being extended.'],
        ]);
    }
}
