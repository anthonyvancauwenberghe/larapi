<?php

namespace Modules\Schedule\Entities;

use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Schedule\Attributes\ScheduleAttributes;
use Modules\Machine\Entities\Machine;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\Schedule\Attributes\WeekDaysAttributes;
use Modules\User\Entities\User;

/**
 * Class Schedule.
 *
 * @property string $_id
 */
class WeekDay implements WeekDaysAttributes
{
    use ModelFactory;
}
