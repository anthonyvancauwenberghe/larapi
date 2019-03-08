<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.09.18
 * Time: 13:37.
 */

namespace Modules\Schedule\Attributes;

interface ScheduleAttributes
{
    const ID = '_id';
    const USER_ID = 'user_id';
    const ALIAS = 'alias';
    const TIMEZONE = 'timezone';
    const WEEK_DAYS = 'week_days';
    const EXCEPTIONS = 'exceptions';
    const RANDOMIZE = 'randomize';
}
