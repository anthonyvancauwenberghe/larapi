<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 31.10.18
 * Time: 09:06
 */

namespace Modules\Machine\Permissions;


interface MachinePermissions
{
    const SHOW_MACHINE = 'machine.show';
    const INDEX_MACHINE = 'machine.index';
    const UPDATE_MACHINE = 'machine.update';
    const CREATE_MACHINE = 'machine.create';
    const DELETE_MACHINE = 'machine.delete';
}