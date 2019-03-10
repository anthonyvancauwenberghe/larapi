<?php

namespace Modules\Proxy\Listeners;

use Modules\Proxy\Events\ProxyUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProxyDeletedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProxyUpdatedEvent  $event
     * @return void
     */
    public function handle(ProxyUpdatedEvent $event)
    {
        //
    }
}
