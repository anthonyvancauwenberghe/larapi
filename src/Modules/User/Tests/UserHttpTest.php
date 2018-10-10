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
    public function testExample()
    {
        $http = $this->http('GET', '/v1/user');
        $http->assertStatus(200);
    }
}
