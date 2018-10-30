<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 14.10.18
 * Time: 19:31.
 */

namespace Modules\Machine\Events;

use Foundation\Abstracts\Events\Event;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Transformers\MachineTransformer;

class MachineUpdatedEvent extends Event implements ShouldBroadcast
{
    public $listeners = [];

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

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->machine->user_id);
    }

    public function broadcastAs()
    {
        return 'machine.updated';
    }

    public function broadcastWith()
    {
        return MachineTransformer::resource($this->machine)->serialize();
    }
}
