<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Foundation\Generator\Abstracts\FileGeneratorCommand;
use Foundation\Generator\Events\RouteGeneratedEvent;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RouteMakeCommand extends FileGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route file for the specified module';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'route';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'route.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Routes';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = RouteGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'MODULE_NAME' => ucfirst($this->getModuleName()),
            'CAPS_MODULE_NAME' => strtoupper($this->getModuleName()),
            'VERSION' => $this->getVersion()
        ];
    }

    protected function getVersion() :string {
        //TODO IMPLEMENT ASKING FOR VERSION
        return 'v1';
    }

    protected function afterGeneration(): void
    {
        $this->info("Don't forget to add permissions to the Permission model!");
    }


    protected function getFileName() :string
    {
        return strtolower(Str::plural($this->getModuleName())).'.'.$this->getVersion() . '.php';
    }


}
