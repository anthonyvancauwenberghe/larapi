<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.02.19
 * Time: 17:50.
 */

namespace Modules\Synchronisation\States;

class SynchronisationState
{
    const CLIENT_STOPPED = 'stopped';
    const CLIENT_RUNNING = 'client_running';

    const CLIENT_HARD_DESYNC = 'client_hard_desync';
    const CLIENT_HARD_DESYNC_RUNNING = 'client_hard_desync_running';
    const CLIENT_SOFT_DESYNC = 'client_soft_desync';

    const SCRIPT_RUNNING = 'script_running';
    const SCRIPT_PAUSED = 'script_paused';
    const SCRIPT_UNSTOPPABLE = 'script_unstoppable';
}
