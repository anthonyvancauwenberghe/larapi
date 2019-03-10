<?php

namespace Modules\User\Entities;

use Foundation\Abstracts\Models\Model;
use Foundation\Contracts\Ownable;
use Foundation\Traits\Cacheable;
use Foundation\Traits\ModelFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Modules\Authorization\Traits\HasRoles;
use Modules\Notification\Traits\ReceivesWebNotifications;
use Modules\User\Policies\UserPolicy;

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
class User extends Model implements AuthorizableContract, AuthenticatableContract, Ownable
{
    use Notifiable,
        Authorizable,
        Authenticatable,
        Cacheable,
        HasRoles,
        ReceivesWebNotifications,
        ModelFactory;

    protected $policies = [
        UserPolicy::class
    ];

    protected $observers = [

    ];

    protected $guard_name = 'api';

    /**
     * @var string
     */
    protected $table = 'users';

    protected $with = ['roles', 'permissions'];

    public $cacheTime = 60;

    public $secondaryCacheIndexes = ['identity_id'];

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'email_verified' => 'bool',
    ];

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
