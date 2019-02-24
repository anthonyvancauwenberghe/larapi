<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.02.19
 * Time: 18:40
 */

namespace Modules\Synchronisation\Generators;

use Modules\Synchronisation\States\ApplicationState;
use Modules\Synchronisation\States\ClientState;

class SynchronisationInitialStateGenerator
{
    protected $clientState;
    protected $applicationState;

    /**
     * SynchronisationInitialStateGenerator constructor.
     * @param ClientState $clientState
     * @param ApplicationState $applicationState
     */
    public function __construct(ClientState $clientState, ApplicationState $applicationState)
    {
        $this->clientState = $clientState;
        $this->applicationState = $applicationState;
    }

    public function generate(){

    }


}
