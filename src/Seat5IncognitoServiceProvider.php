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

namespace WokeUpChoseViolence\Seat5Incognito;

use Seat\Services\AbstractSeatPlugin;
use WokeUpChoseViolence\Seat5Incognito\Commands\Update\CorpContracts;
use WokeUpChoseViolence\Seat5Incognito\Commands\Update\CorpMembers;
use WokeUpChoseViolence\Seat5Incognito\Commands\Update\Corp;
use WokeUpChoseViolence\Seat5Incognito\Commands\Update\CorpMembersLocations;
use WokeUpChoseViolence\Seat5Incognito\Commands\Update\CorpMembersOnline;
use WokeUpChoseViolence\Seat5Incognito\Database\CorpJobSeeders;

/**
 * Class Seat5IncognitoServiceProvider.
 *
 * @package WokeUpChoseViolence\Seat5Incognito
 */
class Seat5IncognitoServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->add_commands();
        $this->add_routes();
        $this->add_views();
        $this->add_translations();
        $this->add_migrations();
    }

    public function register()
    {
        $this->mergeConfigRecursivelyFrom(__DIR__ . '/Config/characterlocationmap.sidebar.php', 'package.sidebar');
        $this->mergeConfigFrom(__DIR__ . '/Config/characterlocationmap.config.php', 'web.config');
        $this->mergeConfigFrom(__DIR__ . '/Config/characterlocationmap.seat.php', 'seat');
        $this->mergeConfigFrom(__DIR__ . '/Config/characterlocationmap.locale.php', 'web.locale');
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
     * Register cli commands.
     */
    private function add_commands()
    {
        $this->commands([
            Corp::class,
            CorpMembers::class,
            CorpContracts::class,
            CorpMembersOnline::class,
            CorpMembersLocations::class,
        ]);
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
        return 'SeAT V5 Tools for Incognito Mode (corp)';
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
        return 'https://github.com/Woke-Up-Chose-Violence/seat-5-incognito';
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
        return 'seat-5-incognito';
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
        return 'woke-up-chose-violence';
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigRecursivelyFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge_recursive(require $path, $config));
    }
}
