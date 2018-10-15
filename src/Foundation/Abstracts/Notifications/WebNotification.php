<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:50
 */

namespace Foundation\Abstracts\Notifications;


use Foundation\Channels\DatabaseNotificationChannel;
use Foundation\Channels\WebNotificationChannel;
use Illuminate\Notifications\Notification;

abstract class WebNotification extends Notification
{
    private $targetModel;

    /**
     * WebNotification constructor.
     * @param $targetModel
     */
    public function __construct($targetModel)
    {
        $this->targetModel = $targetModel;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title(),
            'message' => $this->message(),
            'target' => get_short_class_name($this->targetModel),
            'target_id' => $this->targetModel->getKey(),
            'tag' => $this->tag(),
        ];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {
        $notification = $notifiable->unreadNotifications->last();
        return [
            'id' => $notification->getKey(),
            'target_id' => $this->targetModel->getKey(),
            'target' => get_short_class_name($this->targetModel),
            'tag' => $this->tag(),
            'title' => $this->title(),
            'message' => $this->message(),
            'is_read' => isset($notification->read_at) ? true : false
        ];
    }

    /**
     * The title for the web notification
     * @return string
     */
    abstract protected function title(): string;

    /**
     * The message for the web notification
     * @return string
     */
    abstract protected function message(): string;

    /**
     * The tag for the web notification
     * success | info | warning | danger
     * @return string
     */
    protected function tag()
    {
        return 'info';
    }

    /**
     * Do not change the order database must be called before broadcast.
     * Otherwise we cannot get the appropriate id to broadcast
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            DatabaseNotificationChannel::class,
            WebNotificationChannel::class
        ];
    }
}
