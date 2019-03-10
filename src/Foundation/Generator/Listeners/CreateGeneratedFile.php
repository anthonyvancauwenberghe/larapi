<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 20:39
 */

namespace Foundation\Generator\Listeners;


use Foundation\Abstracts\Listeners\Listener;
use Foundation\Generator\Events\FileGeneratedEvent;
use Foundation\Generator\Support\Stub;
use Nwidart\Modules\Generators\FileGenerator;

class CreateGeneratedFile extends Listener
{
    /**
     * Handle the event.
     *
     * @param  FileGeneratedEvent $event
     * @return void
     */
    public function handle(FileGeneratedEvent $event)
    {
        (new FileGenerator(
            $event->getFilePath(),
            $this->getContents($event->getStubName(), $event->getStubOptions())
        ))->generate();
    }

    /**
     * @return string
     */
    protected function getContents(string $stubName, array $stubOptions): string
    {
        return (new Stub($stubName, $stubOptions))->render();
    }
}
