<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Modules\Authorization\Entities\Permission;
use \Modules\Machine\Permissions\MachinePermissions;

Route::get('/{id}', 'MachineController@show')->middleware(['permission:'.MachinePermissions::SHOW_MACHINE]);
Route::patch('/{id}', 'MachineController@update')->middleware(['permission:'.MachinePermissions::UPDATE_MACHINE]);
Route::delete('/{id}', 'MachineController@destroy')->middleware(['permission:'.MachinePermissions::DELETE_MACHINE]);

Route::post('/', 'MachineController@store')->middleware(['permission:'.MachinePermissions::CREATE_MACHINE]);
Route::get('/', 'MachineController@index')->middleware(['permission:'.MachinePermissions::SHOW_MACHINE]);
