<?php

namespace Modules\Account\Entities;

use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Account\Attributes\AccountAttributes;
use Modules\Machine\Entities\Machine;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\User\Entities\User;

/**
 * Class Account.
 *
 * @property string $_id
 */
class Account extends MongoModel implements Ownable, AccountAttributes
{
    use OwnedByUser, ModelFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $collection = 'accounts';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'online' => 'boolean',
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

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
