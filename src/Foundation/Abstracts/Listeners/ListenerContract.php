<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:40
 */

namespace Foundation\Abstracts\Listeners;

interface ListenerContract
{
    public function handle($event): void;

    public function failed($event, $exception): void;
}
