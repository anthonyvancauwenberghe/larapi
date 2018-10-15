<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 22:56
 */

namespace Foundation\Tests;


use Foundation\Abstracts\Tests\HttpTest;
use MongoDB\BSON\ObjectId;

class BroadcastTest extends HttpTest
{
    public function testPrivateChannelAuthenticationSuccess()
    {
        $user = $this->getHttpUser();
        $id = $user->getKey();
        $response = $this->http('POST', '/broadcasting/auth', [
            'socket_id' => '125200.2991064',
            'channel_name' => 'private-user.' . $id
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('auth', $this->decodeHttpContent($response->content(), false));
    }

    public function testPrivateChannelAuthenticationForbidden()
    {
        $response = $this->http('POST', '/broadcasting/auth', [
            'socket_id' => '125200.2991064',
            'channel_name' => 'private-user.' . new ObjectId()
        ]);
        $response->assertStatus(403);
    }

}
