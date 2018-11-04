<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 12:06.
 */

namespace Modules\User\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Modules\Authorization\Transformers\PermissionTransformer;
use Modules\Authorization\Transformers\RoleTransformer;
use Modules\User\Entities\User;

class UserTransformer extends Transformer
{
    public $include = [
        'roles',
        'permissions',
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
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->name,
            'email_verified' => $this->email_verified,
            'gender'         => $this->gender,
            'provider'       => $this->provider,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'roles'          => $this->transformRoles($this->resource),
            'permissions'    => $this->transformPermissions($this->resource),
        ];
    }

    public function transformRoles(User $user)
    {
        return collect(RoleTransformer::collection($user->roles)->serialize())->flatten();
    }

    public function transformPermissions(User $user)
    {
        return collect(PermissionTransformer::collection($user->getAllPermissions())->serialize())->flatten();
    }
}
