<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\Machine\Services;

use Modules\Machine\Contracts\NotificationServiceContract;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Events\MachineRegisteredEvent;

class Notification implements NotificationServiceContract
{
    public function find($id): ?Machine
    {
        return Machine::find($id);
    }

    public function update($id, $data): Machine
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
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
}
