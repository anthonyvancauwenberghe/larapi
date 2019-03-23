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

use \Modules\Account\Permissions\AccountPermissions;

Route::get('/{id}', 'AccountController@show')->middleware(['permission:'.AccountPermissions::SHOW_ACCOUNT]);
Route::patch('/{id}', 'AccountController@update')->middleware(['permission:'.AccountPermissions::UPDATE_ACCOUNT]);
Route::delete('/{id}', 'AccountController@destroy')->middleware(['permission:'.AccountPermissions::DELETE_ACCOUNT]);
Route::post('/', 'AccountController@store')->middleware(['permission:'.AccountPermissions::CREATE_ACCOUNT]);
Route::get('/', 'AccountController@index')->middleware(['permission:'.AccountPermissions::INDEX_ACCOUNT]);
