<?php

namespace Modules\Machine\Tests;

use Foundation\Abstracts\Tests\HttpTest;

class MachineHttpTest extends HttpTest
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindMachine()
    {
        $user = $this->getHttpUser();
        $machine = $user->machines->first();
        $http = $this->http('GET', '/v1/machines/'.$machine->id);
        //dd($this->decodeHttpContent($http->getContent(), false));
        $http->assertStatus(200);
    }
}
