<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Account\Events;

use Foundation\Abstracts\Events\Event;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Account\Entities\Account;
use Modules\Account\Transformers\AccountTransformer;

class AccountUpdatedEvent extends Event implements ShouldBroadcast
{
    public $listeners = [];

    /**
     * @var Account
     */
    public $Account;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param $user
     */
    public function __construct(Account $Account)
    {
        $this->Account = $Account;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->Account->user_id);
    }

    public function broadcastAs()
    {
        return 'Account.updated';
    }

    public function broadcastWith()
    {
        return AccountTransformer::resource($this->Account)->serialize();
    }
}
