<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 19:23
 */

namespace Foundation\Guard\Abstracts;

use Foundation\Guard\Contracts\GuardContract;
use Foundation\Guard\Resolvers\ExceptionResolver;

abstract class Guard implements GuardContract
{
    protected $exception;

    public function exception(): \Throwable
    {
        return (new ExceptionResolver($this->exception))->resolve();
    }

}
