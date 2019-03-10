<?php

namespace Modules\Proxy\Entities;

use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Proxy\Attributes\ProxyAttributes;
use Modules\Machine\Entities\Machine;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\Proxy\Policies\ProxyPolicy;
use Modules\User\Entities\User;

/**
 * Class Proxy.
 *
 * @property string $_id
 */
class Proxy extends MongoModel implements Ownable, ProxyAttributes
{
    use OwnedByUser, ModelFactory, SoftDeletes;

    protected $policies = [
        ProxyPolicy::class
    ];

    protected $observers = [

    ];

    /**
     * @var string
     */
    protected $collection = 'proxies';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'monitor' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_alive_at',
        'last_checked_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
