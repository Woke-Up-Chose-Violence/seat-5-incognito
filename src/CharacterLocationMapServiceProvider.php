<?php
/*
This file is part of SeAT

Copyright (C) 2015 to 2020  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace tehraven\Seat\CharacterLocationMap;

use Illuminate\Support\Facades\Gate;
use Seat\Services\AbstractSeatPlugin;

/**
 * Class CharacterLocationMapServiceProvider.
 *
 * @package tehraven\Seat\CharacterLocationMap
 */
class CharacterLocationMapServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->add_routes();

        // Uncomment this block to add API documentation
        // $this->add_api_endpoints();

        $this->add_publications();

        $this->add_views();

        $this->add_translations();

        $this->add_migrations();

        /*
        Gate::define('view-all-character-locations', function ($user) {
            print_r($user->roles);
            print_r($user->roles());
            return false;
        });
        */
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/Menu/characterlocationmap.character.php', 'package.character.menu');
        $this->mergeConfigFrom(__DIR__ . '/Config/Menu/characterlocationmap.sidebar.php', 'package.menu');

        $this->mergeConfigFrom(__DIR__ . '/Config/characterlocationmap.config.php', 'package.locale');
        $this->mergeConfigFrom(__DIR__ . '/Config/characterlocationmap.locale.php', 'package.locale');

        $this->registerPermissions(__DIR__ . '/Config/Permissions/character.php', 'character');
    }

    /**
     * Include routes.
     */
    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    /**
     * Import API annotations used to generate Swagger documentation (using Open Api Specifications syntax).
     */
    private function add_api_endpoints()
    {
        $this->registerApiAnnotationsPath([
            __DIR__ . '/Http/Resources',
            __DIR__ . '/Http/Controllers/Api/V2',
        ]);
    }

    /**
     * Add content which must be published (generally, configuration files or static ones).
     */
    private function add_publications()
    {
        $this->publishes([
            __DIR__ . '/resources/css' => public_path('web/css'),
            __DIR__ . '/resources/img' => public_path('your-package/img'),
            __DIR__ . '/resources/js' => public_path('your-package/js'),
        ], ['public', 'seat']);
    }

    /**
     * Import translations.
     */
    private function add_translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'characterlocationmap');
    }

    /**
     * Import views.
     */
    private function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'characterlocationmap');
    }

    /**
     * Add SDE tables to be imported.
     */
    private function add_sde_tables()
    {
        $this->registerSdeTables([
            'mapJumps',
        ]);
    }

    /**
     * Import database migrations.
     */
    private function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     * @example SeAT Web
     *
     */
    public function getName(): string
    {
        return 'Character Location Map';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/tehraven/seat-character-location-map';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     * @example web
     *
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-character-location-map';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     * @example eveseat
     *
     */
    public function getPackagistVendorName(): string
    {
        return 'tehraven';
    }

    /**
     * Return the plugin installed version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return config('characterlocationmap.config.version');
    }
}
