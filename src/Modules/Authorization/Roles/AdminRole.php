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
use Modules\Authorization\Managers\PermissionManager;
use Modules\Machine\Permissions\MachinePermissions;
use Modules\Proxy\Permissions\ProxyPermissions;
use Modules\Schedule\Permissions\SchedulePermissions;

class AdminRole extends AbstractRole
{
    protected $role = Role::ADMIN;

    public function permissions()
    {
        return PermissionManager::getAllPermissions();
    }
}
