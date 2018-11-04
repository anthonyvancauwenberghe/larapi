<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:15.
 */

namespace Modules\Machine\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Machine\Entities\Machine;

/**
 * Interface MachineServiceContract.
 */
interface MachineServiceContract
{
    /**
     * @param int $userId
     *
     * @return Collection | Machine[]
     */
    public function getByUserId($userId);

    /**
     * @param $id
     *
     * @return Machine|null
     */
    public function find($id): ?Machine;

    /**
     * @param $id
     * @param $data
     *
     * @return Machine
     */
    public function update($id, $data): Machine;

    /**
     * @param $data
     *
     * @return Machine
     */
    public function create($data): Machine;

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id): bool;

    /**
     * @param $id
     * @param $data
     */
    public function heartbeat($id, $data): void;
}
