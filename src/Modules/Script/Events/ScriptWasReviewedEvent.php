<?php

namespace Modules\Script\Events;

use Foundation\Abstracts\Events\Event;
use Modules\Script\Entities\Script;
use Modules\Script\Entities\ScriptReview;

class ScriptWasReviewedEvent extends Event
{

    /**
     * The listeners that will be fired when the event is dispatched.
     * @var array
     */
    public $listeners = [];

    /**
     * @var Script
     */
    public $scriptReview;

    /**
     * Create a new event instance.
     *
     */
    public function __construct(ScriptReview $scriptReview)
    {
        $this->scriptReview = $scriptReview;
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
