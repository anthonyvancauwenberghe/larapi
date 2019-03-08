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

Route::get('/{id}', 'ScheduleController@show')->middleware(['permission:'.Permission::SHOW_SCHEDULE]);
Route::patch('/{id}', 'ScheduleController@update')->middleware(['permission:'.Permission::UPDATE_SCHEDULE]);
Route::delete('/{id}', 'ScheduleController@destroy')->middleware(['permission:'.Permission::DELETE_SCHEDULE]);
Route::post('/', 'ScheduleController@store')->middleware(['permission:'.Permission::CREATE_SCHEDULE]);
Route::get('/', 'ScheduleController@index')->middleware(['permission:'.Permission::INDEX_SCHEDULE]);
