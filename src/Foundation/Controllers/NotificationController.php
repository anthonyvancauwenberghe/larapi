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
        get_authenticated_user()->unreadNotifications()->find($id)->markAsRead();
        return response()->json([
            "success"
        ]);
    }
}
