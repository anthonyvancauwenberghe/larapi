<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31
 */

namespace Modules\User\Events;


use Foundation\Abstracts\Events\Event;
use Modules\User\Entities\User;
use Modules\User\Listeners\SendNewlyRegisteredUserWelcomeNotification;

class UserRegisteredEvent extends Event
{
    public $listeners = [
        SendNewlyRegisteredUserWelcomeNotification::class
    ];

    public $user;

    /**
     * UserRegisteredEvent constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


}
