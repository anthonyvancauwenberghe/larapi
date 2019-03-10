<?php

namespace Modules\Proxy\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Proxy\Events\ProxyUpdatedEvent;

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
