<?php

namespace Modules\User\Entities;

use Foundation\Abstracts\MongoModel;
use Foundation\Contracts\Ownable;
use Foundation\Traits\Cacheable;
use Foundation\Traits\Notifiable;
use Foundation\Traits\OwnedByUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Modules\Auth0\Traits\Auth0Model;

/**
 * Class User.
 *
 * @property string $_id
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $name
 * @property string $avatar
 * @property string $provider
 */
class User extends MongoModel implements AuthorizableContract, AuthenticatableContract, Ownable
{
    use Notifiable, Authorizable, Authenticatable, OwnedByUser, Cacheable, Auth0Model;

    /**
     * @var string
     */
    protected $collection = 'users';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function ownerId()
    {
        return $this->id;
    }

}
