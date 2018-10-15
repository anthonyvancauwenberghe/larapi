<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:44
 */

namespace Modules\User\Listeners;


use Foundation\Abstracts\Listeners\Listener;
use Modules\User\Events\UserRegisteredEvent;
use Modules\User\Notifications\UserRegisteredNotification;

class NewlyRegisteredUserListener extends Listener
{

    /**
     * @param UserRegisteredEvent $event
     */
    public function handle($event): void
    {
        $event->user->notify(new UserRegisteredNotification($event->user));
    }

    /**
     * @param UserRegisteredEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {

    }

}
