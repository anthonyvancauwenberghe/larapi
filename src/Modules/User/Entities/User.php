<?php

namespace Modules\User\Entities;

use Foundation\Abstracts\MongoModel;
use Foundation\Contracts\Cacheable;
use Foundation\Contracts\Ownable;
use Foundation\Traits\OwnedByUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends MongoModel implements AuthorizableContract, AuthenticatableContract, Cacheable, Ownable
{
    use Notifiable, Authorizable, Authenticatable, OwnedByUser;

    protected $collection = 'users';

    protected $guarded = [];

    public function ownerId()
    {
        return $this->id;
    }


}
