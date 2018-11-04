<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:15.
 */

namespace Modules\Account\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Account\Entities\Account;
use Modules\Machine\Entities\Machine;

/**
 * Interface AccountServiceContract
 * @package Modules\Account\Contracts
 */
interface AccountServiceContract
{

    /**
     * @param int $userId
     * @return Collection | Account[]
     */
    public function getByUserId($userId);

    /**
     * @param $id
     * @return Account|null
     */
    public function find($id): ?Account;

    /**
     * @param $id
     * @param $data
     * @return Account
     */
    public function update($id, $data): Account;

    /**
     * @param $data
     * @return Account
     */
    public function create($data): Account;

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * @param $id
     * @param $data
     */
    public function heartbeat($id, $data): void;

    public function assignToMachine($id, Machine $machine);

    public function unlinkFromMachine($id);

}
