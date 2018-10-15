<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Auth0\Login\Contract\Auth0UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Auth0\Services\Auth0Service;
use Modules\User\Entities\User;

abstract class HttpTest extends \Foundation\Abstracts\Tests\TestCase
{
    /**
     * @var Auth0Service
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = $this->app->make(Auth0UserRepository::class);
    }

    /**
     * @return User | Authenticatable
     */
    protected function getHttpUser()
    {
        return $this->service->getPredefinedUser();
    }

    protected function decodeHttpContent($content, $unwrap = true)
    {
        if ($unwrap) {
            return json_decode($content, true)['data'];
        }

        return json_decode($content, true);
    }

    protected function http(string $method, string $route, array $payload = [])
    {
        return $this->sendRequest($method, $route, $payload, true);
    }

    private function sendRequest(string $method, string $route, array $payload = [], $authenticated = true): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->json($method, env('API_URL').'/'.$route, $payload, $authenticated ? [
            'Authorization' => 'Bearer '.$this->service->getPredefinedUserTokenData()->id_token,
        ] : []);
    }

    protected function sendRequestWithToken($token, string $method, string $route, array $payload = [], $authenticated = true): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->json($method, env('API_URL').'/'.$route, $payload, $authenticated ? [
            'Authorization' => 'Bearer '.$token,
        ] : []);
    }

    protected function httpNoAuth(string $method, string $route, array $payload = [])
    {
        return $this->sendRequest($method, $route, $payload, false);
    }
}
