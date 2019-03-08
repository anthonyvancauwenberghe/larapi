<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Schedule\Events;

use Foundation\Abstracts\Events\Event;
use Modules\Schedule\Entities\Schedule;
use Modules\Schedule\Listeners\ScheduleCreatedListener;

class ScheduleCreatedEvent extends Event
{
    public $listeners = [
        ScheduleCreatedListener::class,
    ];

    /**
     * @var Schedule
     */
    public $schedule;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param $user
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }
}
