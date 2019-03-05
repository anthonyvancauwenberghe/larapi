<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Proxy\Events;

use Foundation\Abstracts\Events\Event;
use Modules\Proxy\Entities\Proxy;
use Modules\Proxy\Listeners\ProxyCreatedListener;

class ProxyCreatedEvent extends Event
{
    public $listeners = [
        ProxyCreatedListener::class,
    ];

    /**
     * @var Proxy
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
}
