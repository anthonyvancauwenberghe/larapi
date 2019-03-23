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

use \Modules\Proxy\Permissions\ProxyPermissions;

Route::get('/{id}', 'ProxyController@show')->middleware(['permission:'.ProxyPermissions::SHOW_PROXY]);
Route::patch('/{id}', 'ProxyController@update')->middleware(['permission:'.ProxyPermissions::UPDATE_PROXY]);
Route::delete('/{id}', 'ProxyController@destroy')->middleware(['permission:'.ProxyPermissions::DELETE_PROXY]);

Route::post('/', 'ProxyController@store')->middleware(['permission:'.ProxyPermissions::CREATE_PROXY]);
Route::get('/', 'ProxyController@index')->middleware(['permission:'.ProxyPermissions::INDEX_PROXY]);
