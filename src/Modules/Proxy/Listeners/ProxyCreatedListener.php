<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:44.
 */

namespace Modules\Proxy\Listeners;

use Foundation\Abstracts\Listeners\Listener;
use Modules\Proxy\Events\ProxyCreatedEvent;

class ProxyCreatedListener extends Listener
{
    /**
     * @param ProxyCreatedEvent $event
     */
    public function handle($event): void
    {
    }

    /**
     * @param ProxyCreatedEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {
    }
}
