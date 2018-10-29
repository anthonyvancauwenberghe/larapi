<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 12:40
 */

namespace Modules\Notification\Transformers;


use Foundation\Abstracts\Transformers\Transformer;

class NotificationTransformer extends Transformer
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
        $notification = (object) $this->data;
        $resource = [
            'id'      => $this->getKey(),
            'title'   => $notification->title,
            'message' => $notification->message,
            'target'  => $notification->target,
            'tag'     => $notification->tag,
            'is_read' => isset($this->read_at) ? true : false,
        ];

        return $resource;
    }
}
