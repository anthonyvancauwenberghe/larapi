<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:19.
 */

namespace Modules\Authorization\Roles;

use Modules\Authorization\Abstracts\AbstractRole;
use Modules\Authorization\Entities\Role;

class ScripterRole extends AbstractRole
{
    protected $role = Role::SCRIPTER;

    protected function permissions(){
        return MemberRole::getPermissions();
    }
}
