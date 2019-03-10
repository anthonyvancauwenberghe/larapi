<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 17:15.
 */

namespace Foundation\Generator\Abstracts;

use Foundation\Generator\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractGeneratorCommand extends GeneratorCommand
{
    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName;

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub;

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        return $this->getModulePath().$this->filePath.'/'.$this->getFileName();
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return $this->getClassName().'.php';
    }

    protected function getModulePath(): string
    {
        return get_module_path($this->getModuleName());
    }

    protected function getModuleName(): string
    {
        return once(function () {
            return Str::studly($this->askModuleName());
        });
    }

    private function askModuleName(): string
    {
        $moduleName = $this->argument('module') ?? $this->ask('For what module would you like to generate a '.$this->getGeneratorName().'.');

        if ($moduleName === null) {
            throw new \Exception('Name of module not set.');
        }

        return $moduleName;
    }

    /**
     * Get class namespace.
     *
     *
     * @return string
     */
    public function getClassNamespace($module = null): string
    {
        return $this->getModuleNamespace().str_replace('/', '\\', $this->filePath);
    }

    protected function getModuleNamespace(): string
    {
        return 'Modules'.'\\'.$this->getModuleName();
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $this->beforeGeneration();
        $stub = (new Stub($this->stubName(), $this->stubOptions()))->render();
        $this->afterGeneration();

        return $stub;
    }

    protected function beforeGeneration(): void
    {
    }

    protected function afterGeneration(): void
    {
    }

    abstract protected function stubOptions(): array;

    protected function getClassName(): string
    {
        return once(function () {
            return Str::studly($this->askClassName());
        });
    }

    private function askClassName(): string
    {
        $className = $this->argument('name') ?? $this->ask('Specify the name of the '.$this->getGeneratorName().'.');

        if ($className === null) {
            throw new \Exception('Name of '.$this->getGeneratorName().' not set.');
        }

        return $className;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
            ['name', InputArgument::OPTIONAL, 'The name of the '.$this->getGeneratorName().'.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [

        ];
    }

    protected function getGeneratorName(): string
    {
        return $this->generatorName ?? 'class';
    }

    /**
     * Get the stub file name.
     * @return string
     */
    protected function stubName()
    {
        return $this->stub;
    }
}
