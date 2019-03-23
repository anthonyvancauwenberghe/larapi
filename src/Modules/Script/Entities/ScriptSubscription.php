<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Policies\ScriptSubscriptionPolicy;
use Modules\Script\Attributes\ScriptSubscriptionAttributes;
use Foundation\Traits\ModelFactory;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Foundation\Contracts\Ownable;
use Foundation\Traits\OwnedByUser;

/**
 * Class ScriptSubscription.
 *
 * @property string $id
 */
class ScriptSubscription extends Model implements ScriptSubscriptionAttributes, Ownable
{
    use ModelFactory, SoftDeletes, OwnedByUser;

    protected $policies = [
        ScriptSubscriptionPolicy::class
    ];

    protected $observers = [

    ];

    /**
     * @var string
     */
    protected $collection = 'scripts';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
