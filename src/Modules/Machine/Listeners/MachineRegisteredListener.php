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
use Modules\Machine\Notifications\MachineRegisteredNotification;
use Modules\User\Notifications\UserRegisteredNotification;

class MachineRegisteredListener extends Listener
{
    /**
     * @param MachineRegisteredEvent $event
     */
    public function handle($event): void
    {
        $event->machine->user->notify(new MachineRegisteredNotification($event->machine));
    }

    /**
     * @param MachineRegisteredEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {
    }
}
