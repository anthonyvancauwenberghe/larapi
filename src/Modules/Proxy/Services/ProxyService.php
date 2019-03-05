<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\Proxy\Services;

use Carbon\Carbon;
use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\Proxy\Entities\Proxy;
use Modules\Proxy\Events\ProxyCreatedEvent;
use Modules\Proxy\Events\ProxyUpdatedEvent;

class ProxyService implements ProxyServiceContract
{
    public function getByUserId($userId)
    {
        return Proxy::where('user_id', $userId)->get();
    }

    public function find($id): ?Proxy
    {
        if ($id instanceof Proxy) {
            return $id;
        }

        return Proxy::find($id);
    }

    public function update($id, $data): Proxy
    {
        $Proxy = $this->find($id);
        $Proxy->update($data);
        event(new ProxyUpdatedEvent($Proxy));

        return $Proxy;
    }

    public function create($data): Proxy
    {
        $Proxy = Proxy::create($data);
        event(new ProxyCreatedEvent($Proxy));

        return $Proxy;
    }

    public function delete($id): bool
    {
        return Proxy::destroy($id);
    }

    public function healthCheck($id, $data): void
    {
        //TODO DO THE ACTUAL HEALTCHECK
        $this->update($id, [
            'last_alive_at' => Carbon::now(),
            'last_checked_at'         => Carbon::now(),
        ]);
    }
}
