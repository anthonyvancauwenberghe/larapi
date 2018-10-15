<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:44.
 */

namespace Modules\Machine\Listeners;

use Foundation\Abstracts\Listeners\Listener;
use Modules\Machine\Events\MachineRegisteredEvent;
use Modules\User\Notifications\UserRegisteredNotification;

class NewlyRegisteredMachineListener extends Listener
{
    /**
     * @param MachineRegisteredEvent $event
     */
    public function handle($event): void
    {
        $event->machine->notify(new UserRegisteredNotification($event->machine));
    }

    /**
     * @param MachineRegisteredEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {
    }
}
