<?php

/*
|--------------------------------------------------------------------------
| Script API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all API routes for the module Script. These
| routes are automatically loaded by the Larapi framework and assigned to the "api" middleware group.
| Enjoy building your API!
|
*/

use Modules\Authorization\Entities\Permission;
use Modules\Script\Permissions\ScriptPermission;

Route::get('/', 'ScriptController@index')->middleware(['permission:'.ScriptPermission::INDEX_SCRIPT]);
Route::get('/{id}', 'ScriptController@show')->middleware(['permission:'.ScriptPermission::SHOW_SCRIPT]);
Route::post('/', 'ScriptController@store')->middleware(['permission:'.ScriptPermission::CREATE_SCRIPT]);
Route::patch('/{id}', 'ScriptController@update')->middleware(['permission:'.ScriptPermission::UPDATE_SCRIPT]);
Route::delete('/{id}', 'ScriptController@destroy')->middleware(['permission:'.ScriptPermission::DELETE_SCRIPT]);



