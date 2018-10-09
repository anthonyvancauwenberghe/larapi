<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 03.10.18
 * Time: 22:58.
 */

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\HttpTest;

class Auth0AuthenticationTest extends HttpTest
{
    public function testAuthorized()
    {
        $http = $this->http('GET', '/authorized');
        $http->assertStatus(200);
    }

    public function testUnauthorized()
    {
        $http = $this->httpNoAuth('GET', '/authorized');
        $http->assertStatus(401);
    }
}
