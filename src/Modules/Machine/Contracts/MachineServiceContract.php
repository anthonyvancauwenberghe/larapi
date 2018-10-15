<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:15.
 */

namespace Modules\Machine\Contracts;

use Modules\Machine\Entities\Machine;

interface MachineServiceContract
{
    public function find($id): ?Machine;

    public function update($id, $data): Machine;

    public function create($data): Machine;

    public function delete($id): bool;

}
