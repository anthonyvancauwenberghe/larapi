<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 31.10.18
 * Time: 09:06
 */

namespace Modules\Account\Permissions;


interface AccountPermissions
{
    const INDEX_ACCOUNT = 'account.index';
    const SHOW_ACCOUNT = 'account.show';
    const UPDATE_ACCOUNT = 'account.update';
    const CREATE_ACCOUNT = 'account.create';
    const DELETE_ACCOUNT = 'account.delete';
}