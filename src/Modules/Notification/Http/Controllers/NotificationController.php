<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 18:58.
 */

namespace Modules\Notification\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Notification\Contracts\NotificationServiceContract;
use Modules\Notification\Transformers\NotificationTransformer;

class NotificationController extends Controller
{
    protected $service;

    /**
     * NotificationController constructor.
     * @param $service
     */
    public function __construct(NotificationServiceContract $service)
    {
        $this->service = $service;
    }

    public function all()
    {
        return NotificationTransformer::collection($this->service->allNotificationsByUser(get_authenticated_user()));
    }

    public function allUnread()
    {
        return NotificationTransformer::collection($this->service->unreadNotifcationsByUser(get_authenticated_user()));
    }

    public function read($id)
    {
        $this->service->markAsRead($id);

        return response()->json([
            'success',
        ]);
    }

    public function unread($id)
    {
        $this->service->markAsUnread($id);

        return response()->json([
            'success',
        ]);
    }
}
