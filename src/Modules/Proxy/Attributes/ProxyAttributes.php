<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.09.18
 * Time: 13:37.
 */

namespace Modules\Proxy\Attributes;

interface ProxyAttributes
{
    const ID = '_id';
    const USER_ID = 'user_id';
    const ALIAS = 'alias';
    const IP_ADDRESS = 'ip_address';
    const PORT = 'port';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const TYPE = 'type';
    const MONITOR = 'monitor';
    const ANONIMITY_LEVEL = 'anonimity_level';
    const LAST_ALIVE_AT = 'last_alive_at';
    const LAST_CHECKED_AT = 'last_checked_at';
}
