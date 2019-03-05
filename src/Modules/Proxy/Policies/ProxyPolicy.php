<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 12.10.18
 * Time: 01:25.
 */

namespace Modules\Proxy\Policies;

use Foundation\Exceptions\Exception;
use Foundation\Policies\OwnershipPolicy;
use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\User\Entities\User;

class ProxyPolicy extends OwnershipPolicy
{
    protected $service;

    /**
     * ProxyPolicy constructor.
     *
     * @param $service
     */
    public function __construct(ProxyServiceContract $service)
    {
        $this->service = $service;
    }

    public function create(User $user): bool
    {
        $ProxyCount = $this->service->getByUserId($user->id)->count();

        $maxProxyCount = 20;

        if ($ProxyCount > $maxProxyCount) {
            throw new Exception('You Cannot create more than 20 Proxies. Delete one first', 401);
        }
    }
}
