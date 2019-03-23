<?php

namespace Modules\Script\Events;

use Foundation\Abstracts\Events\Event;
use Modules\Script\Entities\Script;
use Modules\Script\Listeners\PublishInitialScriptRelease;

class ScriptWasCreatedEvent extends Event
{

    /**
     * The listeners that will be fired when the event is dispatched.
     * @var array
     */
    public $listeners = [
        PublishInitialScriptRelease::class
    ];

    /**
     * @var Script
     */
    public $script;

    /**
     * Create a new event instance.
     *
     */
    public function __construct(Script $script)
    {
        $this->script = $script;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
