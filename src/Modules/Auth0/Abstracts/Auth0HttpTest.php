<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 05.03.19
 * Time: 11:54
 */

namespace Modules\Auth0\Abstracts;


use Auth0\Login\Contract\Auth0UserRepository;
use Cache;
use Exception;
use Foundation\Abstracts\Tests\HttpTest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Auth0\Services\Auth0Service;
use Modules\Authorization\Traits\UserTestRoles;
use Modules\User\Entities\User;

abstract class Auth0HttpTest extends HttpTest
{
    use UserTestRoles;
    /**
     * @var Auth0Service
     */
    private $auth0;

    private function getAuth0Service()
    {
        if ($this->auth0 === null) {
            $this->auth0 = $this->app->make(Auth0UserRepository::class);
        }

        return $this->auth0;
    }

    protected function http(string $method, string $route, array $payload = [], array $headers = []) :TestResponse
    {
        $headers['Authorization'] = 'Bearer '.$this->getUserAuth0Token()->id_token;
        return parent::http($method, $route, $payload, $headers);
    }

    protected function httpNoAuth(string $method, string $route, array $payload = [], array $headers = []) : \Illuminate\Foundation\Testing\TestResponse
    {
        return parent::http($method, $route, $payload, $headers);
    }

    protected function setUser($user)
    {
        parent::setUser($this->getTestUser());
    }

    private function getTestUser(): User
    {
        $auth0 = \App::make('auth0');
        $tokenInfo = $auth0->decodeJWT($this->getUserAuth0Token()->id_token);
        return $this->getAuth0Service()->getUserByDecodedJWT($tokenInfo);
    }

    private function getUserAuth0Token()
    {
        return Cache::remember('testing:http_access_token', 60 * 60, function () {
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
                throw new Exception('Could not obtain token from Auth0 at ' . env('AUTH0_DOMAIN') . ' for testing.');
            }
        });
    }
}
