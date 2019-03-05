<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Proxy\Events;

use Foundation\Abstracts\Events\Event;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Proxy\Entities\Proxy;
use Modules\Proxy\Transformers\ProxyTransformer;

class ProxyUpdatedEvent extends Event implements ShouldBroadcast
{
    public $listeners = [];

    /**
     * @var proxy
     */
    public $proxy;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param $user
     */
    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->proxy->user_id);
    }

    public function broadcastAs()
    {
        return 'Proxy.updated';
    }

    public function broadcastWith()
    {
        return ProxyTransformer::resource($this->proxy)->serialize();
    }
}
