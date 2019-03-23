<?php

namespace Modules\Script\Tests;

use Modules\Auth0\Abstracts\AuthorizedHttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Script\Contracts\ScriptServiceContract;
use Modules\Script\Entities\Script;
use Modules\Script\Services\ScriptService;
use Modules\Script\Transformers\ScriptTransformer;

class ScriptHttpTest extends AuthorizedHttpTest
{
    protected $roles = Role::ADMIN;

    /**
     * @var Script
     */
    protected $model;

    /**
     * @var ScriptService
     */
    protected $service;

    protected function seedData()
    {
        parent::seedData();
        $this->model = factory(Script::class)->create(['user_id' => $this->getActingUser()->id]);
        $this->service = $this->app->make(ScriptServiceContract::class);
    }

    /**
     * Test retrieving all scripts.
     *
     * @return void
     */
    public function testIndexScripts()
    {
        $response = $this->http('GET', '/v1/scripts');
        $response->assertStatus(200);

        //TODO assert array rule
        /*
        $this->assertEquals(
            ScriptTransformer::collection($this->service->getByUserId($this->getActingUser()->id))->serialize(),
            $response->decode()
        ); */
    }

    /**
     * Test retrieving a Script.
     *
     * @return void
     */
    public function testFindScript()
    {
        $response = $this->http('GET', '/v1/scripts/'.$this->model->id);
        $response->assertStatus(200);

        $this->getActingUser()->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/scripts/'.$this->model->id);
        $response->assertStatus(403);
    }

    /**
     * Test Script Deletion.
     *
     * @return void
     */
    public function testDeleteScript()
    {
        $response = $this->http('DELETE', '/v1/scripts/'.$this->model->id);
        $response->assertStatus(204);
    }

    /**
     * Test Script Creation.
     *
     * @return void
     */
    public function testCreateScript()
    {
        $model = Script::fromFactory()->make([]);
        $response = $this->http('POST', '/v1/scripts', $model->toArray());
        $response->assertStatus(201);

        //TODO ASSERT RESPONSE CONTAINS ATTRIBUTES
        /*
        $this->assertArrayHasKey('username', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('password', $this->decodeHttpResponse($response));
        */
    }

    /**
     * Test Updating a Script.
     *
     * @return void
     */
    public function testUpdateScript()
    {
        /* Test response for a normal user */
        $response = $this->http('PATCH', '/v1/scripts/'.$this->model->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->getActingUser()->syncRoles(Role::GUEST);
        $response = $this->http('PATCH', '/v1/scripts/'.$this->model->id, []);
        $response->assertStatus(403);
    }
}
