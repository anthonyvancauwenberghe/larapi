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

use \Modules\Schedule\Permissions\SchedulePermissions;

Route::get('/{id}', 'ScheduleController@show')->middleware(['permission:'.SchedulePermissions::SHOW_SCHEDULE]);
Route::patch('/{id}', 'ScheduleController@update')->middleware(['permission:'.SchedulePermissions::UPDATE_SCHEDULE]);
Route::delete('/{id}', 'ScheduleController@destroy')->middleware(['permission:'.SchedulePermissions::DELETE_SCHEDULE]);
Route::post('/', 'ScheduleController@store')->middleware(['permission:'.SchedulePermissions::CREATE_SCHEDULE]);
Route::get('/', 'ScheduleController@index')->middleware(['permission:'.SchedulePermissions::INDEX_SCHEDULE]);
