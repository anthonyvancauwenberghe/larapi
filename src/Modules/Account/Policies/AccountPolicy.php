<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 12.10.18
 * Time: 01:25.
 */

namespace Modules\Account\Policies;

use Foundation\Exceptions\Exception;
use Foundation\Policies\OwnershipPolicy;
use Modules\Account\Contracts\AccountServiceContract;
use Modules\User\Entities\User;

class AccountPolicy extends OwnershipPolicy
{
    protected $service;

    /**
     * AccountPolicy constructor.
     *
     * @param $service
     */
    public function __construct(AccountServiceContract $service)
    {
        $this->service = $service;
    }

    public function create(User $user): bool
    {
        $AccountCount = $this->service->getByUserId($user->id)->count();

        $maxAccountCount = 20;

        if ($AccountCount > $maxAccountCount) {
            throw new Exception('You Cannot create more than 20 Accounts. Delete one first', 401);
        }
    }
}
