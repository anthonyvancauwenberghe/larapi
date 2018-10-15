<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 18:10
 */

namespace Modules\Machine\Notifications;


use Foundation\Abstracts\Notifications\WebNotification;
use Modules\Machine\Entities\Machine;

class MachineRegisteredNotification extends WebNotification
{
    /**
     * @var Machine
     */
    public $machine;

    /**
     * UserRegisteredEvent constructor.
     * @param $user
     */
    public function __construct(Machine $machine)
    {
        parent::__construct($machine);
        $this->machine = $machine;
    }

    protected function title(): string
    {
        return 'New machine registered!';
    }

    protected function message(): string
    {
        return "A new machine with ip: ".$this->machine->ip_address . " has been added";
    }

}
