<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 19:32
 */

namespace Foundation\Guard\Contracts;


interface GuardContract
{
    public function condition(): bool;

    public function exception(): \Throwable;
}
