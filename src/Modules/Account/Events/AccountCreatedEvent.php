<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Account\Events;

use Foundation\Abstracts\Events\Event;
use Modules\Account\Entities\Account;
use Modules\Account\Listeners\AccountCreatedListener;

class AccountCreatedEvent extends Event
{
    public $listeners = [
        AccountCreatedListener::class,
    ];

    /**
     * @var Account
     */
    public $account;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param $user
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}
