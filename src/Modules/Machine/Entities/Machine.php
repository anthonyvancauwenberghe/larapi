<?php

namespace Modules\Machine\Entities;

use Foundation\Abstracts\MongoModel;
use Foundation\Contracts\Ownable;
use Foundation\Traits\Notifiable;
use Foundation\Traits\OwnedByUser;
use Modules\User\Entities\User;

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
class Machine extends MongoModel implements Ownable
{
    use Notifiable, OwnedByUser;

    /**
     * @var string
     */
    protected $collection = 'machines';

    /**
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
