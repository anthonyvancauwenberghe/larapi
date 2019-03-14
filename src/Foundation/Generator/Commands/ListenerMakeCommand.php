<?php

namespace Foundation\Generator\Commands;

use Foundation\Core\Larapi;
use Foundation\Generator\Abstracts\ClassGeneratorCommand;
use Foundation\Generator\Events\ListenerGeneratedEvent;
use Foundation\Generator\Support\InputOption;


class ListenerMakeCommand extends ClassGeneratorCommand
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

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = ListenerGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClassName(),
            'EVENTNAME' => $this->getModule()->getNamespace() . '\\' . 'Events' . '\\' . $this->getEventName(),
            'SHORTEVENTNAME' => $this->getEventName(),
        ];
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
            return 'listener-queued.stub';
        }

        return 'listener.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function setOptions(): array
    {

        return [
            ['event', Larapi::getModule($this->getModuleName())->getEvents()->getAllPhpFileNamesWithoutExtension(), InputOption::VALUE_OPTIONAL, 'What is the name of the event that should be listened on?.', null],
            ['queued', null, InputOption::VALUE_IS_BOOL, 'Should the listener be queued?', null],
        ];
    }
}
