<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 17:25
 */

namespace Foundation\Repositories;


use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;

class Auth0UserRepository extends \Auth0\Login\Repository\Auth0UserRepository
{
    protected $service;

    /**
     * Auth0UserRepository constructor.
     * @param $service
     */
    public function __construct(UserServiceContract $service)
    {
        $this->service = $service;
    }


    /* This class is used on api authN to fetch the user based on the jwt.*/
    public function getUserByDecodedJWT($jwt) {
        /*
         * The `sub` claim in the token represents the subject of the token
         * and it is always the `user_id`
         */
        $jwt->user_id = $jwt->sub;

        return $this->upsertUser($jwt);
    }

    public function getUserByUserInfo($userInfo) {
        return $this->upsertUser($userInfo['profile']);
    }

    protected function upsertUser($profile) {

        // Note: Requires configured database access
        $user = $this->service->getUserByAuth0Id($profile->user_id);

        if ($user === null) {
            // If not, create one
            $user = new User();
            $user->email = $profile->email; // you should ask for the email scope
            $user->auth0_id = $profile->user_id;
            $user->name = $profile->name; // you should ask for the name scope
            $user->save();
        }

        return $user;
    }

    public function getUserByIdentifier($identifier) {
        //Get the user info of the user logged in (probably in session)
        $user = \App::make('auth0')->getUser();

        if ($user === null) return null;

        // build the user
        $user = $this->getUserByUserInfo($user);

        // it is not the same user as logged in, it is not valid
        if ($user && $user->auth0id == $identifier) {
            return $user;
        }
    }
}
