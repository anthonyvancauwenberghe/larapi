<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:15
 */

namespace Modules\User\Contracts;


use Modules\User\Entities\User;

interface UserServiceContract
{
    public function getUserByAuth0Id($id) :User;
}
