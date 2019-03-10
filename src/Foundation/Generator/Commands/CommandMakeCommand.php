<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class CommandMakeCommand extends AbstractGeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Artisan command for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'command';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'command.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Console';

    protected function stubOptions(): array
    {
        return [
            'CLASS' => $this->getClassName(),
            'COMMAND_NAME' => $this->getCommandName(),
            'NAMESPACE' => $this->getClassNamespace(),
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
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned.', null],
        ];
    }

    /**
     * @return string
     */
    private function getCommandName()
    {
        return $this->option('command') ?? str_replace('command','',strtolower($this->getModuleName()) . ':' . strtolower($this->getClassName()));
    }
}
