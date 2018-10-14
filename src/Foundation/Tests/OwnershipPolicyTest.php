<?php

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;

class OwnershipPolicyTest extends TestCase
{
    public function testAccessPolicy()
    {
        $user = $this->actAsRandomUser();
        $this->assertTrue($user->can('access', get_authenticated_user()));
    }
}
