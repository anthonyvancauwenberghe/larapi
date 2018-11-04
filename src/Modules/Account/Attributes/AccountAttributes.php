<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.09.18
 * Time: 13:37.
 */

namespace Modules\Account\Attributes;

interface AccountAttributes
{
    const ID = '_id';
    const USER_ID = 'user_id';
    const MACHINE_ID = 'machine_id';
    const PROXY_ID = 'proxy_id';
    const ACTIVITY_ID = 'activity_id';
    const SCRIPT_CONFIG_ID = 'script_config_id';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const GAME = 'game';
    const LOGGED_IN = 'logged_in';
    const PERFORMANCE_MODE = 'performance_mode';
    const BANNED_AT = 'banned_at';
    const LAST_HEARTBEAT_AT = 'last_heartbeat_at';
}
