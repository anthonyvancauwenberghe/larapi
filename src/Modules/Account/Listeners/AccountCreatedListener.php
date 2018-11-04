<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:44.
 */

namespace Modules\Account\Listeners;

use Foundation\Abstracts\Listeners\Listener;
use Modules\Account\Events\AccountCreatedEvent;

class AccountCreatedListener extends Listener
{
    /**
     * @param AccountCreatedEvent $event
     */
    public function handle($event): void
    {
    }

    /**
     * @param AccountCreatedEvent $event
     * @param $exception
     */
    public function failed($event, $exception): void
    {
    }
}
