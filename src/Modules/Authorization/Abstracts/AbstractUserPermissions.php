<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:20
 */

namespace Modules\Authorization\Abstracts;


use Foundation\Exceptions\Exception;

abstract class AbstractUserPermissions
{
    protected $role;

    public abstract function getPermissions(): array;

    public function getRole(): string
    {
        if (!isset($role)) {
            throw new Exception("Role not set for permission " . get_short_class_name(static::class));
        }
        return $this->role;
    }

    public static function get(): array
    {
        return once(function () {
            return (new static);
        })->getPermissions();
    }
}
