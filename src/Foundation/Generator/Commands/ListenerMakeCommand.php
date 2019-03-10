<?php

namespace Foundation\Generator\Commands;

use Foundation\Exceptions\Exception;
use Foundation\Generator\Abstracts\AbstractGeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends AbstractGeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class for the specified module';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'listener';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Listeners';

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClassName(),
            'EVENTNAME' => $this->getModuleNamespace() . '\\' . 'Events' . '\\' . $this->getEventName(),
            'SHORTEVENTNAME' => $this->getEventName(),
        ];
    }

    protected function getEventName(): string
    {
        return once(function () {
            $eventName = $this->option('event') ?? $this->ask('What is the name of the event that should be listened on?', false) ?? "null";
            if ($eventName === null)
                throw new Exception("Eventname for listener not given");
            return $eventName;
        });
    }

    protected function listenerNeedsQueueing(): bool
    {
        return once(function () {
            $option = $this->option('queued');
            return app()->runningInConsole() && !$option ? $this->confirm('Should the listener be queued?', false) : $option;
        });
    }

    protected function afterGeneration(): void
    {
        $this->info("don't forget to add the listener to " . $this->getEventName());
    }

    /**
     * @return string
     */
    protected function stubName(): string
    {
        if ($this->listenerNeedsQueueing()) {
            return '/listener-queued.stub';
        }
        return '/listener.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for.'],
            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued.'],
        ];
    }
}
