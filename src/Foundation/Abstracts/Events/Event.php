<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:33
 */

namespace Foundation\Abstracts\Events;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event implements ShouldQueue
{
    use SerializesModels, Dispatchable;
}
