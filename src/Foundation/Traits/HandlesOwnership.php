<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 16:14.
 */

namespace Foundation\Traits;

use Foundation\Contracts\Ownable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait HandlesOwnership
{
    public function isOwner(?Ownable $model)
    {
        $this->authorize('access', $model);
    }
}
