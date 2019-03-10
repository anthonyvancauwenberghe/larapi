<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:15.
 */

namespace Modules\Schedule\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Schedule\Entities\Schedule;
use Modules\Machine\Entities\Machine;

/**
 * Interface ScheduleServiceContract.
 */
interface ScheduleServiceContract
{
    /**
     * @param int $userId
     *
     * @return Collection | Schedule[]
     */
    public function getByUserId($userId);

    /**
     * @param $id
     *
     * @return Schedule|null
     */
    public function find($id): ?Schedule;

    /**
     * @param $id
     * @param $data
     *
     * @return Schedule
     */
    public function update($id, $data): Schedule;

    /**
     * @param $data
     *
     * @return Schedule
     */
    public function create($data): Schedule;

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id): bool;
}
