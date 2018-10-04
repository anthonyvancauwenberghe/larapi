<?php

namespace Modules\User\Entities;

use Foundation\Abstracts\MongoModel;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends MongoModel implements AuthorizableContract
{
    use Notifiable, Authorizable;

    protected $collection = 'users';
}
