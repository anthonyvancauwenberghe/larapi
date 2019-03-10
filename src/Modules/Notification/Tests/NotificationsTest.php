<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:04.
 */

namespace Modules\Notification\Tests;

use Modules\Auth0\Abstracts\AuthorizedHttpTest;
use Modules\Notification\Transformers\NotificationTransformer;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;
use Modules\User\Events\UserRegisteredEvent;
use Modules\User\Notifications\UserRegisteredNotification;

class NotificationsTest extends AuthorizedHttpTest
{

    protected function seedData()
    {
        parent::seedData();
    }

    public function testUserRegisteredEvent()
    {
        /* Remove the http test user from database so it seems like it's being registered */
        User::destroy($this->getUser()->id);
        $this->expectsEvents(UserRegisteredEvent::class);

        /* Creates a new user & therefore a new userregisteredevent is launched */
        $this->app->make(UserServiceContract::class)->create(factory(User::class)->raw());
    }

    //TODO FIX THIS
    /*public function testDatabaseNotification()
    {
        $notifications = User::find($this->getUser()->getKey())->unreadNotifications->toArray();
        $this->assertCount(1, $notifications);
        $notification = $this->getUser()->unreadNotifications()->first();
        $notificationId = $notification->getKey();
        $response = $this->http('POST', '/v1/notifications/'.$notificationId);
        $response->assertStatus(200);
        $unreadnotifications = User::find($this->getUser()->getKey())->unreadNotifications;
        $this->assertCount(0, $unreadnotifications);
    }*/

    public function testAllNotificationsRoute()
    {
        $user = $this->getUser();
        $user->notifyNow(new UserRegisteredNotification($user));
        $user->notifyNow(new UserRegisteredNotification($user));
        $response = $this->http('GET', '/v1/notifications');
        $response->assertStatus(200);
        $notificationsReponse = $this->decodeHttpResponse($response->getContent());
        $notifications = NotificationTransformer::collection(User::find($user->getKey())->notifications)->jsonSerialize();
        $this->assertEquals($notificationsReponse, (array) $notifications);
    }

    public function testUnreadNotificationsRoute()
    {
        $user = $this->getUser();
        $user->notifyNow(new UserRegisteredNotification($user));
        $notification = $user->unreadNotifications()->first();
        $notificationId = $notification->getKey();
        $response = $this->http('POST', '/v1/notifications/'.$notificationId);
        $response->assertStatus(200);
        $response = $this->http('GET', '/v1/notifications/unread');
        $response->assertStatus(200);
        $notifications = NotificationTransformer::collection(User::find($user->getKey())->unreadNotifications)->jsonSerialize();
        $this->assertEquals($notifications, $this->decodeHttpResponse($response->getContent()));
    }
}
