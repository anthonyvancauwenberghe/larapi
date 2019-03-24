<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 19:21
 */

if (!function_exists('guard')) {
    function guard($guard, $exception = null) :void
    {
        $dispatcher = new \Foundation\Guard\Dispatcher\GuardDispatcher($guard, $exception);
        $dispatcher->dispatch();
    }
}
