<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:44.
 */

namespace Modules\User\Listeners;

use Foundation\Abstracts\Listeners\Listener;
use Modules\Authorization\Entities\Role;
use Modules\User\Events\UserRegisteredEvent;
use Modules\User\Notifications\UserRegisteredNotification;

class RegisteredUserListener extends Listener
{
    /**
     * @param UserRegisteredEvent $event
     */
    public function handle($event): void
    {
        $event->user->notify(new UserRegisteredNotification($event->user));
        $event->user->syncRoles(Role::USER);
    }

    /**
     * @param UserRegisteredEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {
    }
}
