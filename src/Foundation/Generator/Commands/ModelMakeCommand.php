<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Foundation\Generator\Abstracts\ClassGeneratorCommand;
use Foundation\Generator\Events\ModelGeneratedEvent;
use Foundation\Generator\Managers\GeneratorManager;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends ClassGeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'model';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Entities';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = ModelGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClassName(),
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function setOptions(): array
    {
        return [
            ['mongo', null, InputOption::VALUE_OPTIONAL, 'Mongo model.', null],
            ['migration', null, InputOption::VALUE_OPTIONAL, 'Create migration for the model.', null],
        ];
    }

    protected function isMongoModel(): bool
    {
        return once(function () {
            $option = $this->option('mongo');
            if ($option !== null)
                $option = (bool)$option;

            return $option === null ? $this->confirm('Is this model for a mongodb database?', false) : $option;
        });
    }

    public function afterGeneration(): void
    {
        if ($this->needsMigration()) {
            if ($this->isMongoModel()) {
                GeneratorManager::module($this->getModuleName())->createMigration(
                    "Create" . ucfirst($this->getClassName()) . "Collection",
                    strtolower(split_caps_to_underscore(Str::plural($this->getClassName()))),
                    true);
            } else {
                GeneratorManager::module($this->getModuleName())->createMigration(
                    "Create" . ucfirst($this->getClassName() . "Table"),
                    strtolower(split_caps_to_underscore(Str::plural($this->getClassName()))),
                    false);
            }
        }
    }

    protected function getClassName(): string
    {
        $class = parent::getClassName();
        if (strtolower($class) === strtolower($this->getModuleName())) {
            return $class;
        }
        return ucfirst($this->getModuleName()) . ucfirst($class);
    }

    protected function needsMigration(): bool
    {
        return once(function () {
            $option = $this->option('migration');
            if ($option !== null)
                $option = (bool)$option;

            return $option === null ? $this->confirm('Do you want to create a migration for this model?', true) : $option;
        });
    }

    /**
     * @return string
     */
    protected function stubName(): string
    {
        if ($this->isMongoModel()) {
            return 'model-mongo.stub';
        }

        return 'model.stub';
    }
}
