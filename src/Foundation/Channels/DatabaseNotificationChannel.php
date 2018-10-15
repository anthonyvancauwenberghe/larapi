<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 13:27.
 */

namespace Foundation\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseNotificationChannel extends DatabaseChannel
{
    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        return [
            'type'    => get_class($notification),
            'data'    => $this->getData($notifiable, $notification),
            'read_at' => null,
        ];
    }
}
