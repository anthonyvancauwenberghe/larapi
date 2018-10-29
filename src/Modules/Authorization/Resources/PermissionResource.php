<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 23:21.
 */

namespace Modules\Authorization\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'name'  => $this->name,
        ];
    }
}
