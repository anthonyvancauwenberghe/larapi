<?php

namespace Modules\Machine\Entities;

use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\Notifiable;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Mongo\Abstracts\MongoModel;
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
    use Notifiable, OwnedByUser, ModelFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $collection = 'machines';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'online' => 'boolean',
        'active' => 'boolean'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_heartbeat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
