<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 12:32.
 */

namespace Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\User\Notifications\UserRegisteredNotification;

class Test extends HttpTest
{
    public function test()
    {
        $this->getHttpUser();
        //$this->getHttpUser()->notifyNow(new UserRegisteredNotification($this->getHttpUser()));
        $this->assertTrue(true);
    }
}
