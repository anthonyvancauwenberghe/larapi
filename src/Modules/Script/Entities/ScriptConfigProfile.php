<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Policies\ScriptConfigProfilePolicy;
use Modules\Script\Attributes\ScriptConfigProfileAttributes;
use Foundation\Traits\ModelFactory;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Foundation\Contracts\Ownable;
use Foundation\Traits\OwnedByUser;

/**
 * Class ScriptConfigProfile.
 *
 * @property string $id
 */
class ScriptConfigProfile extends Model implements ScriptConfigProfileAttributes, Ownable
{
    use ModelFactory, SoftDeletes, OwnedByUser;

    protected $policies = [
        ScriptConfigProfilePolicy::class
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
