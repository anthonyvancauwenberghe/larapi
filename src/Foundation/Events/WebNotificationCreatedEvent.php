<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 21:43
 */

namespace Foundation\Events;


use Illuminate\Notifications\Events\BroadcastNotificationCreated;

class WebNotificationCreatedEvent extends BroadcastNotificationCreated
{
    public function broadcastAs(){
        return 'notification.created';
    }

    /**
     * Get the data that should be sent with the broadcasted event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}
