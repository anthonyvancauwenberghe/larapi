<?php

namespace Foundation\Generator\Commands;

use Foundation\Exceptions\Exception;
use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Foundation\Generator\Abstracts\ClassGeneratorCommand;
use Foundation\Generator\Events\TestGeneratedEvent;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test class for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'test';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Tests';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = TestGeneratedEvent::class;

    protected $types = [
        "unit",
        "http",
        "service"
    ];

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClassName(),
            'TYPE' => $this->getType()
        ];
    }

    protected function getType(): string
    {
        $type = $this->option('type') ?? $this->anticipate('What type of test would you like to create?', $this->types);
        if ($type === null) {
            throw new Exception('type for test not specified');
        }
        return $type;
    }

    /**
     * @return string
     */
    protected function stubName(): string
    {
        $type = $this->getType();

        if (in_array($type, $this->types))
            return "$type-test.stub";

        throw new Exception("Test type not supported");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function setOptions(): array
    {
        return [
            ['type', null, InputOption::VALUE_OPTIONAL, 'Indicates the type of the test.']
        ];
    }
}
