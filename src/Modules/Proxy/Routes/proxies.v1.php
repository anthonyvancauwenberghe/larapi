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

Route::get('/{id}', 'ProxyController@show')->middleware(['permission:'.Permission::SHOW_PROXY]);
Route::patch('/{id}', 'ProxyController@update')->middleware(['permission:'.Permission::UPDATE_PROXY]);
Route::delete('/{id}', 'ProxyController@destroy')->middleware(['permission:'.Permission::DELETE_PROXY]);

Route::post('/', 'ProxyController@store')->middleware(['permission:'.Permission::CREATE_PROXY]);
Route::get('/', 'ProxyController@index')->middleware(['permission:'.Permission::INDEX_PROXY]);
