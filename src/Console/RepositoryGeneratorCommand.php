<?php

namespace Jenhacool\Repository\Console;

use Illuminate\Console\GeneratorCommand;

class RepositoryGeneratorCommand extends GeneratorCommand
{
    protected $name = 'make:repository';

    protected $description = 'Create a new repository';

    protected $type = 'Repository';

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $model = trim($this->argument('name'));

        return $this->replaceNamespace($stub, $name)->replaceModel($stub, $model)->replaceClass($stub, $name);
    }

    protected function replaceModel(&$stub, $model)
    {
        $stub = str_replace('DummyModel', $model, $stub);

        return $this;
    }

    protected function getNameInput()
    {
        return trim($this->argument('name') . 'Repository');
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . config('repository.directory');
    }
}