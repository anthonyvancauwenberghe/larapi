<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Machine\Events;

use Foundation\Abstracts\Events\Event;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Listeners\NewlyRegisteredMachineListener;
use Modules\User\Entities\User;

class MachineRegisteredEvent extends Event
{
    public $listeners = [
        NewlyRegisteredMachineListener::class,
    ];

    /**
     * @var Machine
     */
    public $machine;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param $user
     */
    public function __construct(Machine $machine)
    {
        $this->machine = $machine;
    }
}
