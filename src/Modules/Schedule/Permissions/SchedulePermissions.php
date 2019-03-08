<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 31.10.18
 * Time: 09:06.
 */

namespace Modules\Schedule\Permissions;

interface SchedulePermissions
{
    const INDEX_SCHEDULE = 'schedule.index';
    const SHOW_SCHEDULE = 'schedule.show';
    const UPDATE_SCHEDULE = 'schedule.update';
    const CREATE_SCHEDULE = 'schedule.create';
    const DELETE_SCHEDULE = 'schedule.delete';
}
