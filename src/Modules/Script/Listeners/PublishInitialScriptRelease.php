<?php

namespace Modules\Script\Listeners;

use Modules\Script\Contracts\ScriptServiceContract;
use Modules\Script\Entities\ScriptRelease;
use Modules\Script\Events\ScriptWasCreatedEvent;
use Foundation\Abstracts\Listeners\Listener;
use Modules\Script\Support\Version;

class PublishInitialScriptRelease extends Listener
{
    protected $service;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ScriptServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  ScriptWasCreatedEvent $event
     * @return void
     */
    public function handle(ScriptWasCreatedEvent $event): void
    {
        $this->service->releaseVersion($event->script,[
            ScriptRelease::TYPE => "MINOR",
            ScriptRelease::CHANGELOG => "Initial script release"
        ]);
    }


    /**
     * Handle the event.
     *
     * @param  ScriptWasCreatedEvent $event
     * @param  \Exception $exception
     * @return void
     */
    public function failed(ScriptWasCreatedEvent $event, $exception): void
    {
        //
    }

}
