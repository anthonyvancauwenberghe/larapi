<?php

namespace Modules\Machine\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\Machine\Entities\Machine;

class MachineServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserMachines()
    {
        $user = $this->actAsRandomUser();
        $machines = $user->machines->toArray();

        $this->assertNotEmpty($machines);
    }

    public function testMachineBelongsToUser()
    {
        $machine = Machine::first();
        $user = $machine->user;
        $this->assertNotNull($user);
    }
}
