<?php

namespace Modules\User\Entities;

use Foundation\Abstracts\MongoModel;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends MongoModel implements AuthorizableContract, Authenticatable
{
    use Notifiable, Authorizable, \Illuminate\Auth\Authenticatable;

    protected $collection = 'users';

    protected $guarded = [];

}
