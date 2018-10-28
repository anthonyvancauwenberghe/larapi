<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 28.10.18
 * Time: 17:25
 */

namespace Modules\Demo\Jobs;


use Foundation\Abstracts\Jobs\Job;
use Modules\Auth0\Contracts\Auth0ServiceContract;
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
     * @var User
     */
    protected $user;


    public function handle()
    {
        $this->boot();
        $this->alterMachineData();
    }

    protected function boot()
    {
        $this->machineService = app()->make(MachineServiceContract::class);
        $this->user = app()->make(Auth0ServiceContract::class)->getTestUser();
    }

    protected function alterMachineData()
    {
        foreach ($this->machineService->allByUserId($this->user->id) as $machine) {
            $this->machineService->heartbeat($machine, [
                'cpu_usage' => rand(0, 100),
                'memory_usage' => rand(1, $machine->memory_available)
            ]);
        }
    }
}
