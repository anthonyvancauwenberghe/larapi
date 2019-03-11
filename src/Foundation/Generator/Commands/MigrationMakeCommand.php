<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MigrationMakeCommand extends AbstractGeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'migration';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Database/Migrations';

    protected function stubOptions(): array
    {
        return [
            'CLASS' => $this->getClassName(),
            'NAMESPACE' => $this->getClassNamespace(),
            "TABLE" => $this->getTableName()
        ];
    }

    protected function getTableName(): string
    {
        return once(function () {
            return $this->option('table') ?? $this->ask('What is the name of the table/collection?', strtolower(Str::plural($this->getModuleName())));
        });
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['mongo', null, InputOption::VALUE_OPTIONAL, 'Mongo migration.', null],
            ['table', null, InputOption::VALUE_OPTIONAL, 'Name of the table/collection.', null],
        ];
    }

    protected function isMongoMigration(): bool
    {
        return once(function () {
            $option = $this->option('mongo');
            if ($option !== null)
                $option = (bool)$option;

            return $option === null ? $this->confirm('Is this migration for a mongodb database?', false) : $option;
        });
    }

    /**
     * @return string
     */
    protected function stubName(): string
    {
        if ($this->isMongoMigration()) {
            return 'migration-mongo.stub';
        }

        return 'migration.stub';
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        return $this->getModule()->getPath() . $this->filePath . '/' . $this->getDestinationFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getDestinationFileName()
    {
        return date('Y_m_d_His_') . split_caps_to_underscore($this->getClassName());
    }
}
