<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.02.19
 * Time: 18:40.
 */

namespace Modules\Synchronisation\Generators;

use Modules\Synchronisation\States\ApplicationState;
use Modules\Synchronisation\States\SynchronisationState;

class SynchronisationEndStateGenerator
{
    protected $applicationState;

    /**
     * SynchronisationEndStateGenerator constructor.
     * @param ApplicationState $applicationState
     */
    public function __construct(ApplicationState $applicationState)
    {
        $this->applicationState = $applicationState;
    }

    public function generate()
    {
        if (! $this->machineIsAvailable() && $this->appShouldRun()) {
            if ($this->applicationState->paused) {
                return SynchronisationState::SCRIPT_PAUSED;
            }

            return SynchronisationState::SCRIPT_RUNNING;
        }

        return SynchronisationState::CLIENT_STOPPED;
    }

    public function machineIsAvailable(): bool
    {
        return $this->applicationState->machine_id === null; //TODO ADD CHECK FOR WHEN A MACHINE IS ONLINE ITSELF ASWELL
    }

    public function appShouldRun(): bool
    {
        return $this->applicationState->active && ($this->applicationState->schedule === null); //TODO SCHEDULE CHECKING
    }

    public function appShouldBePaused(): bool
    {
        return $this->applicationState->paused;
    }
}
