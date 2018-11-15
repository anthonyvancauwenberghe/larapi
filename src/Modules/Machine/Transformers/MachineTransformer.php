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
    public $availableIncludes = [
        'user',
        'accounts'
    ];

    public $include = [
    ];

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
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
            'user'             => UserTransformer::resource($this->whenLoaded('user')),
            'accounts'         => AccountTransformer::collection($this->whenLoaded('accounts')),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
