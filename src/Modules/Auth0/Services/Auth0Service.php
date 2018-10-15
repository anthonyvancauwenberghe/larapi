<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 13.10.18
 * Time: 20:50.
 */

namespace Modules\Auth0\Services;

use Auth0\Login\Repository\Auth0UserRepository;
use Cache;
use Foundation\Exceptions\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Modules\Auth0\Drivers\Auth0UserProfileStorageDriver;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Events\UserRegisteredEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Auth0Service extends Auth0UserRepository
{
    protected $service;

    /**
     * Auth0UserRepository constructor.
     *
     * @param $service
     */
    public function __construct(UserServiceContract $service)
    {
        $this->service = $service;
    }

    /* This class is used on api authN to fetch the user based on the jwt.*/
    public function getUserByDecodedJWT($jwt)
    {
        /*
         * The `sub` claim in the token represents the subject of the token
         * and it is always the `user_id`
         */
        $jwt->user_id = $jwt->sub;

        return $this->upsertUser($jwt);
    }

    public function getUserByUserInfo($userInfo)
    {
        return $this->upsertUser($userInfo['profile']);
    }

    protected function upsertUser($profile)
    {
        if (!isset($profile->user_id)) {
            throw new BadRequestHttpException('Missing token information: Auth0 user id is not set');
        }
        $identifier = explode('|', $profile->user_id);
        $identityProvider = $identifier[0];
        $id = $identifier[1];

        $user = $this->service->find($id);
        $newUser = false;
        if ($user === null) {
            $user = $this->service->newUser([
                'identity_id' => $id,
            ]);
            $newUser = true;
        }
        $driver = new Auth0UserProfileStorageDriver($user, $profile, $identityProvider);
        $user = $driver->run();

        if ($newUser) {
            event(new UserRegisteredEvent($user));
        }

        return $user;
    }

    public function getPredefinedUser()
    {
        $auth0 = \App::make('auth0');
        $tokenInfo = $auth0->decodeJWT($this->getPredefinedUserTokenData()->id_token);

        return $this->getUserByDecodedJWT($tokenInfo);
    }

    public function getPredefinedUserTokenData(): \stdClass
    {
        return Cache::remember('testing:http_access_token', 60, function () {
            try {
                $httpClient = new Client();
                $response = $httpClient->post(env('AUTH0_DOMAIN').'oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id'  => env('AUTH0_CLIENT_ID'),
                        'username'   => env('AUTH0_TEST_USER_NAME'),
                        'password'   => env('AUTH0_TEST_USER_PASS'),
                        'scope'      => 'openid profile email offline_access',
                    ],
                ]);

                return json_decode($response->getBody()->getContents());
            } catch (ClientException $exception) {
                throw new Exception('Could not obtain token from Auth0 at '.env('AUTH0_DOMAIN').' for testing.');
            }
        });
    }
}
