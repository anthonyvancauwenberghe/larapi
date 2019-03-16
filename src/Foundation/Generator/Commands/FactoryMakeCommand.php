<?php

namespace Foundation\Generator\Commands;

use Foundation\Core\Larapi;
use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Foundation\Generator\Abstracts\ClassGeneratorCommand;
use Foundation\Generator\Events\FactoryGeneratedEvent;
use Symfony\Component\Console\Input\InputOption;

class FactoryMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'factory';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'factory.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Database/factories';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = FactoryGeneratedEvent::class;

    protected function getModelName(): string
    {
        return once(function () {
            return $this->option('model') ?? $this->anticipate('For what model would you like to generate a factory?', [$this->getModuleName()], $this->getModuleName());
        });
    }

    protected function getClassName() :string
    {
        return $this->getModelName().'Factory';
    }

    protected function stubOptions(): array
    {
        return [
            'CLASS' => $this->getClassName(),
            'MODEL' => $this->getModelName(),
            'MODEL_NAMESPACE' => $this->getModule()->getNamespace().'\\'.'Entities'.'\\'.$this->getModelName(),
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function setOptions() :array
    {
        return [
            ['model', null, InputOption::VALUE_OPTIONAL, 'The Model name for the factory.', null],
        ];
    }

    protected function handleCommandOption($shortcut, $type, $question, $default){
        return $this->anticipate('What is the name of the model?', Larapi::getModule($this->getModuleName())->getModels()->getAllPhpFileNamesWithoutExtension(),Larapi::getModule($this->getModuleName())->getModels()->getAllPhpFileNamesWithoutExtension()[0]);
    }
}
