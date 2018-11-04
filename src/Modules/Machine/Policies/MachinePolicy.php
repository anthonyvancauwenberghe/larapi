<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 12.10.18
 * Time: 01:25.
 */

namespace Modules\Machine\Policies;

use Foundation\Exceptions\Exception;
use Foundation\Policies\OwnershipPolicy;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\User\Entities\User;

class MachinePolicy extends OwnershipPolicy
{
    protected $service;

    /**
     * MachinePolicy constructor.
     *
     * @param $service
     */
    public function __construct(MachineServiceContract $service)
    {
        $this->service = $service;
    }

    public function create(User $user): bool
    {
        $machineCount = $this->service->getByUserId($user->id)->count();

        $maxMachineCount = 20;

        if ($machineCount > $maxMachineCount) {
            throw new Exception('You Cannot create more than 20 machines. Delete one first', 401);
        }
    }
}
