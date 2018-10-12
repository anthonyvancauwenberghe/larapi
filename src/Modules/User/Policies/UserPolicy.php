<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 12.10.18
 * Time: 01:25
 */

namespace Modules\User\Policies;

use Foundation\Policies\OwnershipPolicy;

class UserPolicy extends OwnershipPolicy
{
    public function access($user, $model): bool
    {
        return true;
    }

}
