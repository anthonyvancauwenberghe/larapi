<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Schedule\Events;

use Foundation\Abstracts\Events\Event;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Schedule\Entities\Schedule;
use Modules\Schedule\Listeners\ScheduleUpdatedListener;
use Modules\Schedule\Transformers\ScheduleTransformer;

class ScheduleUpdatedEvent extends Event implements ShouldBroadcast
{
    public $listeners = [
        ScheduleUpdatedListener::class
    ];

    /**
     * @var schedule
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

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->schedule->user_id);
    }

    public function broadcastAs()
    {
        return 'Schedule.updated';
    }

    public function broadcastWith()
    {
        return ScheduleTransformer::resource($this->schedule)->serialize();
    }
}
