<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 23:21.
 */

namespace Modules\Machine\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
{
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
            'cpu_clock'        => 5,
            'online'           => true,
            'last_heartbeat'   => Carbon::now()->toDateTimeString(),
            'created_at'       => Carbon::now()->toDateTimeString(),
            'updated_at'       => Carbon::now()->toDateTimeString(),
        ];
    }
}
