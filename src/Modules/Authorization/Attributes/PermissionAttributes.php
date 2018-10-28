<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 22-10-18
 * Time: 20:02.
 */

namespace Modules\Authorization\Attributes;

interface PermissionAttributes
{
    const INDEX_USERS = 'index.users';
    const CREATE_USER = 'create.user';

    const ASSIGN_ROLES = 'assign.roles';

    const SHOW_MACHINE = 'show.machine';
    const UPDATE_MACHINE = 'test.machine';
    const CREATE_MACHINE = 'create.machine';
    const DELETE_MACHINE = 'delete.machine';
}
