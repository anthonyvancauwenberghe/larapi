<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:04
 */

namespace Foundation\Tests;


use Foundation\Abstracts\Tests\HttpTest;
use Foundation\Events\WebNotificationCreatedEvent;
use Foundation\Resources\NotificationResource;
use Modules\User\Entities\User;
use Modules\User\Events\UserRegisteredEvent;
use Modules\User\Notifications\UserRegisteredNotification;

class NotificationsTest extends HttpTest
{
    public function testUserRegisteredEvent()
    {
        /* Remove the http test user from database so it seems like it's being registered */
        $user = $this->getHttpUser();
        User::destroy($user->id);
        $this->expectsEvents(UserRegisteredEvent::class);

        /* Creates a new user & therefore a new userregisteredevent is launched */
        $this->getHttpUser();
    }

    public function testDatabaseNotification()
    {
        $user = $this->getHttpUser();

        $notifications = User::find($user->getKey())->unreadNotifications->toArray();
        $this->assertCount(1, $notifications);
        $notification = $user->unreadNotifications()->first();
        $notificationId = $notification->getKey();
        $response = $this->http('POST', 'v1/notifications/' . $notificationId);
        $response->assertStatus(200);
        $unreadnotifications = User::find($user->getKey())->unreadNotifications;
        $this->assertCount(0, $unreadnotifications);
    }

    public function testAllNotificationsRoute()
    {
        $user = $this->getHttpUser();
        $user->notifyNow(new UserRegisteredNotification($user));
        $user->notifyNow(new UserRegisteredNotification($user));
        $response = $this->http('GET', 'v1/notifications');
        $response->assertStatus(200);
        $notificationsReponse = $this->decodeHttpContent($response->getContent());
        $notifications = NotificationResource::collection(User::find($user->getKey())->notifications)->jsonSerialize();
        $this->assertEquals($notificationsReponse, (array)$notifications);
    }

    public function testUnreadNotificationsRoute()
    {
        $user = $this->getHttpUser();
        $user->notifyNow(new UserRegisteredNotification($user));
        $notification = $user->unreadNotifications()->first();
        $notificationId = $notification->getKey();
        $response = $this->http('POST', 'v1/notifications/' . $notificationId);
        $response->assertStatus(200);
        $response = $this->http('GET', 'v1/notifications/unread');
        $response->assertStatus(200);
        $notifications = NotificationResource::collection(User::find($user->getKey())->unreadNotifications)->jsonSerialize();
        $this->assertEquals($notifications, $this->decodeHttpContent($response->getContent()));
    }
}
