<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56
 */

namespace Foundation\Abstracts\Tests;

use Cache;
use Foundation\Repositories\Auth0UserRepository;
use GuzzleHttp\Client;
use Modules\User\Services\UserService;
use Tests\TestCase;

class HttpTest extends TestCase
{

    protected function getTestUser()
    {
        $auth0 = \App::make('auth0');
        $repository = new Auth0UserRepository(new UserService());
        $tokenInfo = $auth0->decodeJWT($this->getUserTokenData()->id_token);
        return $repository->getUserByDecodedJWT($tokenInfo);
    }

    private function getUserTokenData(): \stdClass
    {
        return Cache::remember('testing:http_access_token', 60, function () {
            $httpClient = new Client();
            $response = $httpClient->post(config('laravel-auth0.domain') . 'oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 'Dik7up1ZsRePpdZNjzrHIAUHe8mCb3RK',
                    'username' => 'admin@admin.com',
                    'password' => 'admin',
                    'scope' => 'openid profile email offline_access'
                ]
            ]);
            return json_decode($response->getBody()->getContents());
        });
    }

    protected function http(string $method, string $route, array $payload = array())
    {
        return $this->sendRequest($method, $route, $payload, true);
    }

    private function sendRequest(string $method, string $route, array $payload = array(), $authenticated = true): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->json($method, $route, $payload, $authenticated ? [
            "Authorization" => "Bearer " . $this->getUserTokenData()->id_token
        ] : []);
    }

    protected function httpNoAuth(string $method, string $route, array $payload = array())
    {
        return $this->sendRequest($method, $route, $payload, false);
    }
}
