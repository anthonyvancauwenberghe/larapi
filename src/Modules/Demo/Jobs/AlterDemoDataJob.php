<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 28.10.18
 * Time: 17:25.
 */

namespace Modules\Demo\Jobs;

use Foundation\Abstracts\Jobs\Job;
use Modules\Account\Contracts\AccountServiceContract;
use Modules\Account\Services\AccountService;
use Modules\Auth0\Contracts\Auth0ServiceContract;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Services\MachineService;
use Modules\User\Entities\User;

class AlterDemoDataJob extends Job
{
    /**
     * @var MachineService
     */
    protected $machineService;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var User
     */
    protected $user;

    public function __construct()
    {
        $this->machineService = app()->make(MachineServiceContract::class);
        $this->accountService = app()->make(AccountServiceContract::class);
        $this->user = app()->make(Auth0ServiceContract::class)->getTestUser(Role::ADMIN);
    }

    public function handle()
    {
        $this->alterMachineData();
    }

    protected function alterMachineData()
    {
        foreach ($this->machineService->getByUserId($this->user->id) as $machine) {
            $this->machineService->heartbeat($machine, [
                'cpu_usage'    => rand(0, 100),
                'memory_usage' => rand(1, $machine->memory_available),
            ]);
            $this->machineService->update($machine, ['online' => (bool) rand(0, 1)]);
        }
    }

    protected function alterAccountData()
    {
        foreach ($this->accountService->getByUserId($this->user->id) as $account) {
            $this->accountService->heartbeat($account, [
                'cpu_usage'    => rand(0, 100),
                'memory_usage' => rand(1, $account->memory_available),
            ]);
            $this->accountService->update($account, ['online' => (bool) rand(0, 1)]);
        }
    }
}
