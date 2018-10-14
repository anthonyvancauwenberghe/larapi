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

Route::get('/', 'FoundationController@api');

Route::get('/authorized', 'FoundationController@authorized')->middleware('auth0');

Route::get('/v1/notifications', 'NotificationController@all')->middleware('auth0');
Route::get('/v1/notifications/unread', 'NotificationController@allUnread')->middleware('auth0');
Route::post('/v1/notifications/{id}', 'NotificationController@read')->middleware('auth0');
