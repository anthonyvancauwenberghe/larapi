<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 20:02
 */

namespace Modules\Authorization\Attributes;

use Modules\Authorization\Entities\Permission;

interface RolePermissions
{
    const USER = [
        Permission::SHOW_MACHINE,
        Permission::CREATE_MACHINE,
        Permission::UPDATE_MACHINE,
        Permission::DELETE_MACHINE
    ];

    const GUEST = [
        Permission::SHOW_MACHINE,
    ];
}