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

Route::get('/{id}', 'AccountController@show')->middleware(['permission:'.Permission::SHOW_ACCOUNT]);
Route::patch('/{id}', 'AccountController@update')->middleware(['permission:'.Permission::UPDATE_ACCOUNT]);
Route::delete('/{id}', 'AccountController@destroy')->middleware(['permission:'.Permission::DELETE_ACCOUNT]);

Route::post('/', 'AccountController@store')->middleware(['permission:'.Permission::CREATE_ACCOUNT]);
Route::get('/', 'AccountController@index')->middleware(['permission:'.Permission::INDEX_ACCOUNT]);
