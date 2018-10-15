<?php

namespace Modules\User\Tests;

use Foundation\Abstracts\Tests\HttpTest;

class UserHttpTest extends HttpTest
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUser()
    {
        $http = $this->http('GET', '/v1/users');
        $http->assertStatus(200);
    }
}
