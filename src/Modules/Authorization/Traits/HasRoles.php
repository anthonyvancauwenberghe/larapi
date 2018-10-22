<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 19:34.
 */

namespace Modules\Authorization\Traits;

use Modules\Authorization\Entities\Role;

trait HasRoles
{
    use \Maklad\Permission\Traits\HasRoles {
        \Maklad\Permission\Traits\HasRoles::assignRole as assignRoleParent;
        \Maklad\Permission\Traits\HasRoles::removeRole as removeRoleParent;
        \Maklad\Permission\Traits\HasRoles::givePermissionTo as givePermissionParent;
        \Maklad\Permission\Traits\HasRoles::revokePermissionTo as revokePermissionParent;
    }

    public function assignRole(...$roles)
    {
        $roles = $this->assignRoleParent($roles);
        unset($this->roles);

        return $roles;
    }

    public function removeRole(...$roles)
    {
        $roles = $this->removeRoleParent($roles);
        unset($this->roles);

        return $roles;
    }

    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->givePermissionParent($permissions);
        unset($this->permissions);

        return $permissions;
    }

    public function revokePermissionTo(...$permissions)
    {
        $permissions = $this->revokePermissionParent($permissions);
        unset($this->permissions);

        return $permissions;
    }

    public function isAdmin() :bool
    {
        return $this->hasRole(Role::ADMIN);
    }
}
