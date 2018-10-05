<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\User\Services;

use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;

class UserService implements UserServiceContract
{
    public function getUserByAuth0Id($id) :User
    {
        return User::where('auth0_id', $id)->first();
    }
}
