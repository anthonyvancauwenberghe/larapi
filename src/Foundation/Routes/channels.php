<?php


/*
|--------------------------------------------------------------------------
| Channel Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Broadcast routes for your application. These
| routes are loaded by the BroadcastServiceProvider.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
