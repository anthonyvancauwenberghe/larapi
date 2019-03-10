<?php

namespace Modules\Schedule\Tests;

use Modules\Schedule\Contracts\ScheduleServiceContract;
use Modules\Schedule\Entities\Schedule;
use Modules\Schedule\Services\ScheduleService;
use Modules\Schedule\Transformers\ScheduleTransformer;
use Modules\Auth0\Abstracts\AuthorizedHttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Entities\Machine;
use Modules\User\Entities\User;


class ScheduleHttpTest extends AuthorizedHttpTest
{
    protected $roles = Role::MEMBER;

    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * @var ScheduleService
     */
    protected $service;

    protected function seedData()
    {
        parent::seedData();
        $this->schedule = factory(Schedule::class)->create(['user_id' => $this->getUser()->id]);
        $this->service = $this->app->make(ScheduleServiceContract::class);
    }

    public function testAllSchedules()
    {
        $response = $this->http('GET', '/v1/schedules');
        $response->assertStatus(200);
        $this->assertEquals(
            ScheduleTransformer::collection($this->service->getByUserId($this->getUser()->id))->serialize(),
            $response->decode()
        );
    }

    public function testAllSchedulesAsAdmin(){
        $this->getUser()->syncRoles(Role::ADMIN);
        $this->testAllSchedules();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindSchedule()
    {
        $response = $this->http('GET', '/v1/schedules/' . $this->schedule->id);
        $response->assertStatus(200);

        $this->getUser()->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/schedules/' . $this->schedule->id);
        $response->assertStatus(403);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindScheduleWithRelations()
    {
        $response = $this->http('GET', '/v1/schedules/' . $this->schedule->id, ['include' => 'user', 'limit' => 3]);
        $response->assertStatus(200);

        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));

        $response = $this->http('GET', '/v1/schedules/' . $this->schedule->id);
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response));
    }

    public function testUnauthorizedAccessSchedule()
    {
        $user = factory(User::class)->create();
        $schedule = factory(Schedule::class)->create(['user_id' => $user->id]);

        $response = $this->http('GET', '/v1/schedules/' . $schedule->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteSchedule()
    {
        $user = factory(User::class)->create();
        $Schedule = factory(Schedule::class)->create(['user_id' => $user->id]);
        $response = $this->http('DELETE', '/v1/schedules/' . $Schedule->id);
        $response->assertStatus(403);
    }

    public function testDeleteSchedule()
    {
        $response = $this->http('DELETE', '/v1/schedules/' . $this->schedule->id);
        $response->assertStatus(204);

        $this->assertNull(Schedule::find($this->schedule->id));
    }

    public function testAdminAccessSchedule()
    {
        $user = factory(User::class)->create();
        $Schedule = factory(Schedule::class)->create(['user_id' => $user->id]);

        $this->getUser()->syncRoles(Role::ADMIN);
        $response = $this->http('GET', '/v1/schedules/' . $Schedule->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateSchedule()
    {
        $schedule = Schedule::fromFactory()->make([
            'user_id' => $this->getUser()->id,
        ]);
        $response = $this->http('POST', '/v1/schedules', $schedule->toArray());
        $response->assertStatus(201);
        $this->assertArrayHasKey('exceptions', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('week_days', $this->decodeHttpResponse($response));
    }

    /**
     * Update a Schedule test.
     *
     * @return void
     */
    public function testUpdateSchedule()
    {
        /* Test response for a normal user */
        $response = $this->http('PATCH', '/v1/schedules/' . $this->schedule->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->getUser()->syncRoles(Role::GUEST);
        $this->assertFalse($this->getUser()->hasRole(Role::MEMBER));
        $this->assertTrue($this->getUser()->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/schedules/' . $this->schedule->id, []);
        $response->assertStatus(403);
    }
}
