<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 18:10.
 */

namespace Modules\Machine\Notifications;

use Foundation\Abstracts\Notifications\WebNotification;

class MachineRegisteredNotification extends WebNotification
{

    protected function title(): string
    {
        return 'New machine registered!';
    }

    protected function message(): string
    {
        return 'A new machine with ip: '.$this->model->ip_address.' has been added';
    }
}
