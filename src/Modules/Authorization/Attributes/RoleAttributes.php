<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 20:02
 */

namespace Modules\Authorization\Attributes;

interface RoleAttributes
{
    const ADMIN = 'admin';
    const USER = 'user';
    const GUEST = 'guest';
}