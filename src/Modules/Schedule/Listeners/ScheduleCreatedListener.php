<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:44.
 */

namespace Modules\Schedule\Listeners;

use Foundation\Abstracts\Listeners\Listener;
use Modules\Schedule\Events\ScheduleCreatedEvent;

class ScheduleCreatedListener extends Listener
{
    /**
     * @param ScheduleCreatedEvent $event
     */
    public function handle($event): void
    {
    }

    /**
     * @param ScheduleCreatedEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {
    }
}
