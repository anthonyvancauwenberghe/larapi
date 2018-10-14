<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 00:52
 */

namespace Foundation\Collection;

use Foundation\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public $collects = NotificationResource::class;
}
