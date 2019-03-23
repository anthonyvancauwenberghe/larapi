<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 20:02.
 */

namespace Modules\Authorization\Attributes;

use Modules\Account\Permissions\AccountPermissions;
use Modules\Client\Permissions\ClientPermission;
use Modules\Machine\Permissions\MachinePermissions;
use Modules\Proxy\Permissions\ProxyPermissions;
use Modules\Schedule\Permissions\SchedulePermissions;
use Modules\User\Permissions\UserPermissions;

interface Permissions extends UserPermissions, MachinePermissions, AccountPermissions, ProxyPermissions, SchedulePermissions
{
    const ASSIGN_ROLES = 'role.assign';
}
