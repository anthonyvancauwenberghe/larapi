<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 12:06
 */

namespace Modules\User\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Modules\Authorization\Resources\PermissionResource;
use Modules\Authorization\Resources\RoleResource;
use Modules\User\Entities\User;

class UserTransformer extends Transformer
{
    public $relations = [
        'roles',
        'permissions'
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->name,
            'email_verified' => $this->email_verified,
            'gender' => $this->gender,
            'provider' => $this->provider,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function transformRoles(User $user)
    {
        return collect(RoleResource::collection($user->roles))->flatten();
    }

    public function transformPermissions(User $user)
    {
        return collect(PermissionResource::collection($user->getAllPermissions())->toArray(null))->flatten();
    }
}
