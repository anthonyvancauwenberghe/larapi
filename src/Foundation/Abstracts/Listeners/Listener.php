<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:40.
 */

namespace Foundation\Abstracts\Listeners;

use Illuminate\Queue\InteractsWithQueue;

abstract class Listener implements ListenerContract
{
    use InteractsWithQueue;
}
