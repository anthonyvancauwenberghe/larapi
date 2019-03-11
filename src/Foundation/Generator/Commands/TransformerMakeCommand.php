<?php

namespace Foundation\Generator\Commands;

use Foundation\Core\Larapi;
use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class TransformerMakeCommand extends AbstractGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'transformer';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'transformer.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Transformers';

    protected function getModelName(): string
    {
        return once(function () {
            return $this->option('model') ?? $this->anticipate('For what model would you like to generate a transformer?', Larapi::getModule($this->getModuleName())->getModels()->getAllPhpFileNamesWithoutExtension(), $this->getModuleName());
        });
    }

    protected function stubOptions(): array
    {
        return [
            'CLASS' => $this->getClassName(),
            'NAMESPACE' => $this->getClassNamespace(),
            'MODEL' => $this->getModelName(),
            'MODEL_NAMESPACE' => $this->getModule()->getNamespace().'\\'.'Entities'.'\\'.$this->getModelName(),
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
            ['model', null, InputOption::VALUE_OPTIONAL, 'The Model name for the transformer.', null],
        ];
    }
}
