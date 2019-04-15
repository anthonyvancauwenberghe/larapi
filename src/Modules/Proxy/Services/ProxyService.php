<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\Proxy\Services;

use Carbon\Carbon;
use Modules\Proxy\Contracts\ProxyRepositoryContract;
use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\Proxy\Entities\Proxy;
use Modules\Proxy\Events\ProxyCreatedEvent;
use Modules\Proxy\Events\ProxyUpdatedEvent;

class ProxyService implements ProxyServiceContract
{
    protected $repository;

    /**
     * ProxyService constructor.
     * @param $repository
     */
    public function __construct(ProxyRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function getByUserId($userId)
    {
        return Proxy::where('user_id', $userId)->get();
    }

    public function find($id): ?Proxy
    {
        return $this->repository->resolve($id);
    }

    public function update($id, $data): Proxy
    {
        $proxy = $this->repository->update($data, $id);
        event(new ProxyUpdatedEvent($proxy));

        return $proxy;
    }

    public function create($data): Proxy
    {
        $proxy = Proxy::create($data);
        event(new ProxyCreatedEvent($proxy));

        return $proxy;
    }

    public function delete($id): bool
    {
        return $this->find($id)->delete();
    }

    public function healthCheck($id, $data): void
    {
        //TODO DO THE ACTUAL HEALTCHECK
        $this->update($id, [
            'last_alive_at' => Carbon::now(),
            'last_checked_at' => Carbon::now(),
        ]);
    }
}
