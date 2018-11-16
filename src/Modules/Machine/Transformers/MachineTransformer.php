<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:38.
 */

namespace Modules\Machine\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Modules\Account\Transformers\AccountTransformer;
use Modules\Machine\Entities\Machine;
use Modules\User\Transformers\UserTransformer;

class MachineTransformer extends Transformer
{
    public $available = [
        'user'     => UserTransformer::class,
        'accounts' => AccountTransformer::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function transformResource(Machine $machine)
    {
        return [
            'id'               => $machine->id,
            'user_id'          => $machine->user_id,
            'name'             => $machine->name,
            'hostname'         => $machine->hostname,
            'username'         => $machine->username,
            'os'               => $machine->os,
            'hash'             => $machine->hash,
            'active'           => $machine->active,
            'ip_address'       => $machine->ip_address,
            'mac_address'      => $machine->mac_address,
            'memory_usage'     => $machine->memory_usage,
            'memory_available' => $machine->memory_available,
            'cpu_usage'        => $machine->cpu_usage,
            'cpu_clock'        => $machine->cpu_clock,
            'online'           => $machine->online,
            'last_heartbeat'   => $machine->last_heartbeat ?? null,
            'created_at'       => $machine->created_at,
            'updated_at'       => $machine->updated_at,
        ];
    }
}
