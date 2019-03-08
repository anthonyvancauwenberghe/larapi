<?php

namespace Modules\Schedule\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\Schedule\Entities\Schedule;
use Modules\User\Entities\User;

class ScheduleServiceTest extends TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Schedule[]
     */
    protected $schedules;

    protected function seedData()
    {
        parent::seedData();
        $this->user = $this->actAsRandomUser();
        $this->schedules = Schedule::fromFactory(5)->create(['user_id' => $this->user->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserSchedules()
    {
        $Schedules = $this->schedules->toArray();

        $this->assertNotEmpty($Schedules);
    }

    public function testScheduleBelongsToUser()
    {
        $Schedule = Schedule::first();
        $user = $Schedule->user;
        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $Schedule->user);
    }
}
