<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.09.18
 * Time: 13:37.
 */

namespace Modules\Application\Attributes;

interface ApplicationAttributes
{
    const ID = '_id';
    const NAME = 'name';
    const TYPE = 'type';
    const ACTIVE = 'active';
    const USER_ID = 'user_id';
    const MACHINE_ID = 'machine_id';
    const PROXY_ID = 'proxy_id';
    const SCHEDULE_ID = 'schedule_id';
    const SCRIPT_ID = 'script_id';
    const SCRIPT_CONFIG_ID = 'script_config_id';
    const CREDENTIALS = 'credentials';
    const PERFORMANCE_MODE = 'performance_mode';
    const BANNED_AT = 'banned_at';
}
