<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\Schedule\Services;

use Carbon\Carbon;
use Modules\Schedule\Contracts\ScheduleServiceContract;
use Modules\Schedule\Entities\Schedule;
use Modules\Schedule\Events\ScheduleCreatedEvent;
use Modules\Schedule\Events\ScheduleUpdatedEvent;

class ScheduleService implements ScheduleServiceContract
{

    public function getByUserId($userId)
    {
        return Schedule::where('user_id', $userId)->get();
    }

    public function find($id): ?Schedule
    {
        if ($id instanceof Schedule) {
            return $id;
        }

        return Schedule::find($id);
    }

    public function update($id, $data): Schedule
    {
        $Schedule = $this->find($id);
        $Schedule->update($data);
        event(new ScheduleUpdatedEvent($Schedule));

        return $Schedule;
    }

    public function create($data): Schedule
    {
        $Schedule = Schedule::create($data);
        event(new ScheduleCreatedEvent($Schedule));

        return $Schedule;
    }

    public function delete($id): bool
    {
        return $this->find($id)->delete();
    }
}
