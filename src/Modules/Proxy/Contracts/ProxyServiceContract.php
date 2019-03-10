<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:15.
 */

namespace Modules\Proxy\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Proxy\Entities\Proxy;
use Modules\Machine\Entities\Machine;

/**
 * Interface ProxyServiceContract.
 */
interface ProxyServiceContract
{
    /**
     * @param int $userId
     *
     * @return Collection | Proxy[]
     */
    public function getByUserId($userId);

    /**
     * @param $id
     *
     * @return Proxy|null
     */
    public function find($id): ?Proxy;

    /**
     * @param $id
     * @param $data
     *
     * @return Proxy
     */
    public function update($id, $data): Proxy;

    /**
     * @param $data
     *
     * @return Proxy
     */
    public function create($data): Proxy;

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
    public function healthCheck($id, $data): void;
}
