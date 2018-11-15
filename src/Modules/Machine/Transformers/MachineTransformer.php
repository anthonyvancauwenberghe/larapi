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
    public function transformResource()
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'name'             => $this->name,
            'hostname'         => $this->hostname,
            'username'         => $this->username,
            'os'               => $this->os,
            'hash'             => $this->hash,
            'active'           => $this->active,
            'ip_address'       => $this->ip_address,
            'mac_address'      => $this->mac_address,
            'memory_usage'     => $this->memory_usage,
            'memory_available' => $this->memory_available,
            'cpu_usage'        => $this->cpu_usage,
            'cpu_clock'        => $this->cpu_clock,
            'online'           => $this->online,
            'last_heartbeat'   => $this->last_heartbeat ?? null,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
