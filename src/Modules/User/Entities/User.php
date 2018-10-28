<?php

namespace Modules\User\Entities;

use Foundation\Abstracts\SqlModel;
use Foundation\Contracts\Ownable;
use Foundation\Traits\Cacheable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Modules\Authorization\Traits\HasRoles;
use Modules\Notification\Traits\ReceivesWebNotifications;

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
class User extends SqlModel implements AuthorizableContract, AuthenticatableContract, Ownable
{
    use Notifiable,
        Authorizable,
        Authenticatable,
        Cacheable,
        HasRoles,
        ReceivesWebNotifications;

    protected $guard_name = 'api';

    /**
     * @var string
     */
    protected $table = 'users';

    protected $with = ['roles','permissions'];

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

    public function ownedBy()
    {
        return self::class;
    }
}
