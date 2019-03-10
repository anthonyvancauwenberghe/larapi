<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:19
 */

namespace Modules\Authorization\Permissions;


use Modules\Authorization\Abstracts\AbstractUserPermissions;
use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;

class ScripterPermissions extends AbstractUserPermissions
{
    protected $role = Role::MEMBER;

    public function getPermissions(): array
    {
        return [
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
            Permission::DELETE_SCHEDULE
        ];
    }


}
