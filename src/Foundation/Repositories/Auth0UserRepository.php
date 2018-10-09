<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 17:25.
 */

namespace Foundation\Repositories;

use Foundation\Exceptions\Exception;
use Illuminate\Validation\UnauthorizedException;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;
use MongoDB\BSON\ObjectId;

class Auth0UserRepository extends \Auth0\Login\Repository\Auth0UserRepository
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
        if (!isset($profile->user_id))
            throw new Exception("Missing token information: Auth0 user id is not set");

        $identifier = explode('|', $profile->user_id);
        $identityProvider = $identifier[0];
        $id = $identifier[1];

        $user = $this->service->find($id);

        if ($user === null || !$this->userEqualsProfile($user, $profile)) {
            try {

                if ($user === null)
                    $user = new User();

                $user->_id = new ObjectId($id);
                $user->provider = $identityProvider;
                $user->email = $profile->email;
                $user->username = $profile->nickname;
                $user->name = $profile->name;
                $user->avatar = $profile->picture;
                $user->save();
            } catch (\Exception $exception) {
                throw new UnauthorizedException('Profile data is not set in the token ');
            }
        }

        return $user;
    }

    private function userEqualsProfile($user, $profile)
    {
        return $user->username === $profile->nickname && $user->email === $profile->email && $user->name === $profile->name && $user->avatar === $profile->picture;
    }

    public function getUserByIdentifier($identifier)
    {
        //Get the user info of the user logged in (probably in session)
        $user = \App::make('auth0')->getUser();

        if ($user === null) {
            return;
        }

        // build the user
        $user = $this->getUserByUserInfo($user);

        // it is not the same user as logged in, it is not valid
        if ($user && $user->auth0id == $identifier) {
            return $user;
        }
    }
}
