<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 12.10.18
 * Time: 01:25.
 */

namespace Modules\Schedule\Policies;

use Foundation\Exceptions\Exception;
use Foundation\Policies\OwnershipPolicy;
use Modules\Schedule\Contracts\ScheduleServiceContract;
use Modules\User\Entities\User;

class SchedulePolicy extends OwnershipPolicy
{
    protected $service;

    /**
     * SchedulePolicy constructor.
     *
     * @param $service
     */
    public function __construct(ScheduleServiceContract $service)
    {
        $this->service = $service;
    }

    public function create(User $user): bool
    {
        $ScheduleCount = $this->service->getByUserId($user->id)->count();

        $maxScheduleCount = 20;

        if ($ScheduleCount > $maxScheduleCount) {
            throw new Exception('You Cannot create more than 20 Schedules. Delete one first', 401);
        }
    }
}
