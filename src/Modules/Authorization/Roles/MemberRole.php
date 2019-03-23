<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:19.
 */

namespace Modules\Authorization\Roles;

use Modules\Account\Permissions\AccountPermissions;
use Modules\Authorization\Abstracts\AbstractRole;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Permissions\MachinePermissions;
use Modules\Proxy\Permissions\ProxyPermissions;
use Modules\Schedule\Permissions\SchedulePermissions;
use Modules\Script\Permissions\ScriptPermission;

class MemberRole extends AbstractRole
{
    protected $role = Role::MEMBER;

    protected $permissions = [
        AccountPermissions::INDEX_ACCOUNT,
        AccountPermissions::SHOW_ACCOUNT,
        AccountPermissions::CREATE_ACCOUNT,
        AccountPermissions::UPDATE_ACCOUNT,
        AccountPermissions::DELETE_ACCOUNT,

        MachinePermissions::INDEX_MACHINE,
        MachinePermissions::SHOW_MACHINE,
        MachinePermissions::CREATE_MACHINE,
        MachinePermissions::UPDATE_MACHINE,
        MachinePermissions::DELETE_MACHINE,

        ProxyPermissions::INDEX_PROXY,
        ProxyPermissions::SHOW_PROXY,
        ProxyPermissions::CREATE_PROXY,
        ProxyPermissions::UPDATE_PROXY,
        ProxyPermissions::DELETE_PROXY,

        SchedulePermissions::INDEX_SCHEDULE,
        SchedulePermissions::SHOW_SCHEDULE,
        SchedulePermissions::CREATE_SCHEDULE,
        SchedulePermissions::UPDATE_SCHEDULE,
        SchedulePermissions::DELETE_SCHEDULE,

        ScriptPermission::CREATE_SCRIPT,
        ScriptPermission::INDEX_SCRIPT,
        ScriptPermission::SHOW_SCRIPT,
        ScriptPermission::UPDATE_SCRIPT,
        ScriptPermission::DELETE_SCRIPT
    ];
}
