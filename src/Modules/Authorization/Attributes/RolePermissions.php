<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 20:02.
 */

namespace Modules\Authorization\Attributes;

use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;

interface RolePermissions
{
    const RELATIONS = [
        Role::MEMBER => [

            Permission::INDEX_ACCOUNT,
            Permission::SHOW_ACCOUNT,
            Permission::CREATE_ACCOUNT,
            Permission::UPDATE_ACCOUNT,
            Permission::DELETE_ACCOUNT,

            Permission::INDEX_MACHINE,
            Permission::SHOW_MACHINE,
            Permission::CREATE_MACHINE,
            Permission::UPDATE_MACHINE,
            Permission::DELETE_MACHINE,

            Permission::INDEX_PROXY,
            Permission::SHOW_PROXY,
            Permission::CREATE_PROXY,
            Permission::UPDATE_PROXY,
            Permission::DELETE_PROXY,

            Permission::INDEX_SCHEDULE,
            Permission::SHOW_SCHEDULE,
            Permission::CREATE_SCHEDULE,
            Permission::UPDATE_SCHEDULE,
            Permission::DELETE_SCHEDULE,
        ],

        Role::GUEST => [
            Permission::INDEX_ACCOUNT,
            Permission::SHOW_ACCOUNT,
            Permission::INDEX_MACHINE,
            Permission::SHOW_MACHINE,
        ],

        Role::SCRIPTER => [

        ],

        Role::TRUSTED_SCRIPTER => [

        ],
    ];
}
