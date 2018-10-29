<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\Machine\Services;

use Carbon\Carbon;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Events\MachineRegisteredEvent;
use Modules\Machine\Events\MachineUpdatedEvent;

class MachineService implements MachineServiceContract
{
    public function getByUserId($userId)
    {
        return Machine::where('user_id', $userId)->get();
    }

    public function find($id): ?Machine
    {
        if ($id instanceof Machine) {
            return $id;
        }

        return Machine::find($id);
    }

    public function update($id, $data): Machine
    {
        $machine = $this->find($id);
        $machine->update($data);
        event(new MachineUpdatedEvent($machine));

        return $machine;
    }

    public function create($data): Machine
    {
        $machine = Machine::create($data);
        event(new MachineRegisteredEvent($machine));

        return $machine;
    }

    public function delete($id): bool
    {
        return Machine::destroy($id);
    }

    public function heartbeat($id, $data): void
    {
        $this->update($id, [
            'last_heartbeat' => Carbon::now(),
            'memory_usage'   => $data['memory_usage'],
            'cpu_usage'      => $data['cpu_usage'],
            'online'         => true,
        ]);
    }
}
