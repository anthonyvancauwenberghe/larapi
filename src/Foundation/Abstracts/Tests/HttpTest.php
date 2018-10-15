<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Auth0\Login\Contract\Auth0UserRepository;
use Cache;
use Foundation\Exceptions\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\User\Entities\User;

abstract class HttpTest extends \Foundation\Abstracts\Tests\TestCase
{

    /**
     * @var Auth0UserRepository
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
        $auth0 = \App::make('auth0');
        $tokenInfo = $auth0->decodeJWT($this->getUserTokenData()->id_token);

        return $this->service->getUserByDecodedJWT($tokenInfo);
    }

    private function getUserTokenData(): \stdClass
    {
        return Cache::remember('testing:http_access_token', 60, function () {
            try {
                $httpClient = new Client();
                $response = $httpClient->post(env('AUTH0_DOMAIN') . 'oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => env('AUTH0_CLIENT_ID'),
                        'username' => env('AUTH0_TEST_USER_NAME'),
                        'password' => env('AUTH0_TEST_USER_PASS'),
                        'scope' => 'openid profile email offline_access',
                    ],
                ]);
                return json_decode($response->getBody()->getContents());
            } catch (ClientException $exception) {
                throw new Exception("Could not obtain token from Auth0 at " . env('AUTH0_DOMAIN') . " for testing.");
            }
        });
    }

    protected function decodeHttpContent($content, $unwrap = true)
    {
        if ($unwrap)
            return json_decode($content, true)['data'];
        return json_decode($content, true);
    }

    protected function http(string $method, string $route, array $payload = [])
    {
        return $this->sendRequest($method, $route, $payload, true);
    }

    private function sendRequest(string $method, string $route, array $payload = [], $authenticated = true): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->json($method, env('API_URL') . '/' . $route, $payload, $authenticated ? [
            'Authorization' => 'Bearer ' . $this->getUserTokenData()->id_token,
        ] : []);
    }

    protected function sendRequestWithToken($token, string $method, string $route, array $payload = [], $authenticated = true): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->json($method, env('API_URL') . '/' . $route, $payload, $authenticated ? [
            'Authorization' => 'Bearer ' . $token,
        ] : []);
    }

    protected function httpNoAuth(string $method, string $route, array $payload = [])
    {
        return $this->sendRequest($method, $route, $payload, false);
    }
}
