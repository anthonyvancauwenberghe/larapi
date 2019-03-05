<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 31.10.18
 * Time: 09:06.
 */

namespace Modules\Proxy\Permissions;

interface ProxyPermissions
{
    const INDEX_PROXY = 'proxy.index';
    const SHOW_PROXY = 'proxy.show';
    const UPDATE_PROXY = 'proxy.update';
    const CREATE_PROXY = 'proxy.create';
    const DELETE_PROXY = 'proxy.delete';
}
