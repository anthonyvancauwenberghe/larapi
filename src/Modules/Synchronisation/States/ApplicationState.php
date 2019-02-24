<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.02.19
 * Time: 15:52
 */

namespace Modules\Synchronisation\States;


use Modules\Application\Entities\Application;

class ApplicationState
{
    public $script_id;
    public $machine_id;

    public $script_config;
    public $schedule;
    public $proxy;
    public $credentials;

    public $paused;
    public $active;

    /**
     * ApplicationState constructor.
     * @param Application app
     */
    public function __construct(Application $app)
    {
        $this->script_id = $app->script_id;
        $this->machine_id = $app->machine_id;
        $this->script_config = $app->script_config;
        $this->schedule = $app->schedule;
        $this->proxy = $app->proxy;
        $this->credentials = $app->credentials;
        $this->paused = $app->paused;
        $this->active = $app->active;
    }


}
