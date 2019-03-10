<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.02.19
 * Time: 15:52.
 */

namespace Modules\Synchronisation\States;

class ClientState
{
    protected $running;
    protected $paused;
    protected $stoppable;

    protected $script_id;
    protected $machine_id;

    protected $script_config;
    protected $proxy;
}
