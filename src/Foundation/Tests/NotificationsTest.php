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
        $this->expectsEvents(UserRegisteredEvent::class);
        event(new UserRegisteredEvent($this->getHttpUser()));
    }

    public function testDatabaseNotification()
    {
        $user = $this->getHttpUser();
        $user->notifyNow(new UserRegisteredNotification($user));
        $user->notifyNow(new UserRegisteredNotification($user));

        $notifications = User::find($user->getKey())->unreadNotifications;
        $this->assertCount(2, $notifications);
        $notification = $user->unreadNotifications()->first();
        $notificationId = $notification->getKey();
        $response = $this->http('POST', 'v1/notifications/' . $notificationId);
        $response->assertStatus(200);
        $unreadnotifications = User::find($user->getKey())->unreadNotifications;
        $this->assertCount(1, $unreadnotifications);
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
        $user->notifyNow(new UserRegisteredNotification($user));
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
