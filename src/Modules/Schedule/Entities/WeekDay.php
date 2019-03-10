<?php

namespace Modules\Schedule\Entities;

use Foundation\Traits\ModelFactory;
use Modules\Schedule\Attributes\WeekDaysAttributes;

/**
 * Class Schedule.
 *
 * @property string $_id
 */
class WeekDay implements WeekDaysAttributes
{
    use ModelFactory;
}
