<?php

namespace Modules\Machine\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\Machine\Entities\Machine;
use Modules\User\Entities\User;

class MachineServiceTest extends TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Machine[]
     */
    protected $machines;

    protected function seedData()
    {
        parent::seedData();
        $this->user = $this->actAsRandomUser();
        $this->machines = factory(Machine::class, 5)->create(['user_id' => $this->user->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserMachines()
    {
        $machines = $this->machines->toArray();

        $this->assertNotEmpty($machines);
    }

    public function testMachineBelongsToUser()
    {
        $machine = Machine::first();
        $user = $machine->user;
        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $machine->user);
    }
}
