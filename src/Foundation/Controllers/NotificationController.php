<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 18:58
 */

namespace Foundation\Controllers;

use Foundation\Resources\NotificationResource;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotificationController extends Controller
{
    public function all()
    {
        return NotificationResource::collection(get_authenticated_user()->notifications);
    }

    public function allUnread()
    {
        return NotificationResource::collection(get_authenticated_user()->unreadNotifications);
    }

    public function read($id)
    {
        $notification = get_authenticated_user()->unreadNotifications()->find($id);

        if ($notification === null)
            throw new NotFoundHttpException("Could not find notification");

        $notification->markAsRead();
        return response()->json([
            "success"
        ]);
    }
}
