<?php

namespace Modules\Machine\Entities;

use Carbon\Carbon;
use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\Notifiable;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Account\Entities\Account;
use Modules\Machine\Policies\MachinePolicy;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\User\Entities\User;

/**
 * Class User.
 *
 * @property string $_id
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $hostname
 * @property string $username
 * @property string $os
 * @property string $hash
 * @property bool   $active
 * @property string $ip_address
 * @property string $mac_address
 * @property int    $memory_usage
 * @property int    $memory_available
 * @property int    $cpu_usage
 * @property float  $cpu_clock
 * @property bool   $online
 * @property Carbon $last_heartbeat
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Machine extends MongoModel implements Ownable
{
    use Notifiable, OwnedByUser, ModelFactory, SoftDeletes;

    protected $policies = [
        MachinePolicy::class
    ];

    protected $observers = [

    ];

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
        'active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_heartbeat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
