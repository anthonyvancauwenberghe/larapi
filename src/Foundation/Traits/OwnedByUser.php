<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 23:33
 */

namespace Foundation\Traits;


use Modules\User\Entities\User;

trait OwnedByUser
{
    public function ownerId()
    {
        $ownerAttributeName = strtolower(getShortClassName($this->ownedBy())) . '_id';
        return $this->$ownerAttributeName;
    }

    public function ownedBy()
    {
        return User::class;
    }
}
