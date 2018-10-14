<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 00:02
 */

namespace Modules\Auth0\Tests;


use Foundation\Abstracts\Tests\HttpTest;
use Modules\User\Entities\User;

class Auth0Test extends HttpTest
{
    public function testUserIdEqualsIdentityId()
    {
        $user = $this->getHttpUser();
        $this->assertEquals($user->id, $user->identity_id);
        $this->assertEquals($user->_id, $user->identity_id);
    }

    public function testAuthorized()
    {
        $user = $this->getHttpUser();
        $userId = $user->getKey();
        $http = $this->http('GET', '/authorized');
        $http->assertStatus(200);

        $user->update(['email' => 'blablabla@mail.com']);
        $this->assertEquals(User::find($userId)->email, 'blablabla@mail.com');
        $http = $this->http('GET', '/authorized');
        $http->assertStatus(200);

        $this->assertNotEquals(User::find($user->getKey())->email, 'blablabla@mail.com');
    }

    public function testUnauthorized()
    {
        $http = $this->httpNoAuth('GET', '/authorized');
        $http->assertStatus(401);
    }

    public function testUserPrimaryKey()
    {
        $this->assertEquals(factory(User::class)->create()->getKeyName(), 'identity_id');
    }
}
