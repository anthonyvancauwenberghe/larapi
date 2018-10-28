<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 23:21.
 */

namespace Modules\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authorization\Resources\PermissionResource;
use Modules\Authorization\Resources\RoleResource;

class UserResource extends JsonResource
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
            'name'             => $this->name,
            'email'            => $this->name,
            'email_verified'   => $this->email_verified,
            'gender'           => $this->gender,
            'provider'         => $this->provider,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'roles'            => collect(RoleResource::collection($this->roles)->toArray(null))->flatten(),
            'permissions'      => collect(PermissionResource::collection($this->getAllPermissions() )->toArray(null))->flatten()
        ];
    }
}
