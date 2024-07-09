<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'WokeUpChoseViolence\Seat5Incognito\Http\Controllers',
    'prefix'     => 'locations',
    'middleware' => ['web', 'auth', 'locale'],
], function () {

    // Your route definitions go here.
    Route::get('/', [
        'as'   => 'woke-up-chose-violence.home',
        'uses' => 'CharacterMapController@getMap'
    ]);

    // Your route definitions go here.
    Route::get('/region/{region_id}', [
        'as'   => 'woke-up-chose-violence.region',
        'uses' => 'CharacterMapController@getRegionMap'
    ]);

    // Your route definitions go here.
    Route::get('/system/{system_id}', [
        'as'   => 'woke-up-chose-violence.system',
        'uses' => 'CharacterMapController@getSystemMap'
    ]);
});
