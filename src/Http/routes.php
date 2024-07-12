<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'WokeUpChoseViolence\Seat5Incognito\Http\Controllers\CharacterMap',
    'prefix'     => 'locations',
    'middleware' => ['web', 'auth', 'locale'],
], function () {

    // Your route definitions go here.
    Route::get('/', [
        'as'   => 'woke-up-chose-violence.locations.home',
        'uses' => 'CharacterMapController@getMap'
    ]);

    // Your route definitions go here.
    Route::get('/region/{region_id}', [
        'as'   => 'woke-up-chose-violence.locations.region',
        'uses' => 'CharacterMapController@getRegionMap'
    ]);

    // Your route definitions go here.
    Route::get('/system/{system_id}', [
        'as'   => 'woke-up-chose-violence.locations.system',
        'uses' => 'CharacterMapController@getSystemMap'
    ]);
});

Route::group([
    'namespace'  => 'WokeUpChoseViolence\Seat5Incognito\Http\Controllers\BombSettings',
    'prefix'     => 'bomb',
    'middleware' => ['web', 'auth', 'locale'],
], function () {

    // Your route definitions go here.
    Route::get('/', [
        'as'   => 'woke-up-chose-violence.settings.home',
        'uses' => 'BombSettingsController@getSettings'
    ]);

    Route::post('/', [
        'as' => 'woke-up-chose-violence.settings.save',
        'uses' => 'BombSettingsController@saveSettings'
    ]);
});