<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 22:56
 */

namespace Foundation\Tests;


use Foundation\Abstracts\Tests\HttpTest;

class BroadcastTest extends HttpTest
{
    public function testAuth()
    {
        $user = $this->getHttpUser();
        $response = $this->http('POST', '/broadcasting/auth', [
            'socket_id' => '125191.2709135',
            'channel_name' => 'private-user.' . $user->getKey()
        ]);

        $response->assertStatus(200);

    }
}
