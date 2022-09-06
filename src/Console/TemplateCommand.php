<?php

namespace Zareismail\Gutenberg\Console;

use Illuminate\Console\GeneratorCommand;

class TemplateCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gutenberg:template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new template class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Template';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/gutenberg/template.stub');
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
            : __DIR__.str_replace('gutenberg/', '', $stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Gutenberg\Templates';
    }
}
