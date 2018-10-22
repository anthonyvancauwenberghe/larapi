<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 17:27.
 */

namespace Modules\Authorization\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\Authorization\Entities\Role;
use Modules\User\Entities\User;

class AuthorizationTest extends TestCase
{
    public function testRoles()
    {
        $user = User::first();
        $this->assertFalse($user->isAdmin());
        $user->assignRole(Role::ADMIN);
        $this->assertTrue($user->isAdmin());
    }
}
