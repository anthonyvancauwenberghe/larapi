<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 21.02.19
 * Time: 18:34
 */

namespace Modules\Client\Entities;


class ClientState
{
    public $type;
    public $paused;
    public $stoppable;
    public $scriptId;
    public $machineId;
    public $scriptConfig;
    public $proxy;
    public $credentials;

    /**
     * ClientState constructor.
     * @param $type
     * @param $paused
     * @param $stoppable
     * @param $scriptId
     * @param $machineId
     * @param $scriptConfig
     * @param $proxy
     * @param $credentials
     */
    private function __construct(string $type, bool $paused, bool $stoppable, string $scriptId, string $machineId, ?array $scriptConfig, ?array $proxy, ?array $credentials)
    {
        $this->type = $type;
        $this->paused = $paused;
        $this->stoppable = $stoppable;
        $this->scriptId = $scriptId;
        $this->machineId = $machineId;
        $this->scriptConfig = $scriptConfig;
        $this->proxy = $proxy;
        $this->credentials = $credentials;
    }

    public static function create(array $state){
        return new ClientState(
            $state['type'],
            $state['paused'],
            $state['stoppable'],
            $state['scriptId'],
            $state['machineId'],
            $state['script_config'] ??null,
            $state['proxy'] ?? null,
            $state['credentials'] ?? null
        );
    }


}