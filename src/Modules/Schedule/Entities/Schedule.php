<?php

namespace Modules\Schedule\Entities;

use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Schedule\Attributes\ScheduleAttributes;
use Modules\Machine\Entities\Machine;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\Schedule\Policies\SchedulePolicy;
use Modules\User\Entities\User;

/**
 * Class Schedule.
 *
 * @property string $_id
 */
class Schedule extends MongoModel implements Ownable, ScheduleAttributes
{
    use OwnedByUser, ModelFactory, SoftDeletes;

    protected $policies = [
        SchedulePolicy::class
    ];

    protected $observers = [

    ];

    /**
     * @var string
     */
    protected $collection = 'schedules';

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
