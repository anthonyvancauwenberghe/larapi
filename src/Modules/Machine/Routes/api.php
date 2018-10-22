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

Route::get('/{id}', 'MachineController@show')->middleware(['permission:'.Permission::SHOW_MACHINE]);
Route::patch('/{id}', 'MachineController@update')->middleware(['permission:'.Permission::UPDATE_MACHINE]);
Route::delete('/{id}', 'MachineController@destroy')->middleware(['permission:'.Permission::DELETE_MACHINE]);

Route::post('/', 'MachineController@store')->middleware(['permission:'.Permission::CREATE_MACHINE]);
Route::get('/', 'MachineController@index')->middleware(['permission:'.Permission::SHOW_MACHINE]);