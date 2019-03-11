<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.03.19
 * Time: 17:08
 */

namespace Foundation\Generator\Traits;


use Foundation\Generator\Events\FileGeneratedEvent;
use Illuminate\Support\Facades\Event;

trait DispatchedGeneratorEvents
{

    /**
     * @return FileGeneratedEvent[]
     */
    protected function assertAndReturnDispatchedEvents()
    {
        $events = [];
        Event::assertDispatched(FileGeneratedEvent::class, function ($event) use (&$events) {
            $events[] = $event;
            return true;
        });
        return $events;
    }

    /**
     * @param string $class
     * @return FileGeneratedEvent[]
     */
    protected function getDispatchedEvents(string $class)
    {
        $generationEvents = [];
        $events = $this->assertAndReturnDispatchedEvents();
        foreach ($events as $event) {
            if ($event->getGenerationClass() === $class) {
                $generationEvents[] = $event;
            }
        }
        return $generationEvents;
    }

    /**
     * @param string $class
     * @return FileGeneratedEvent
     */
    protected function getFirstDispatchedEvent(string $class)
    {
        $events = $this->getDispatchedEvents($class);
        if (empty($events))
            return null;
        return $events[0];
    }
}
