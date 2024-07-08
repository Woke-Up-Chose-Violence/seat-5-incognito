<?php

namespace WokeUpChoseViolence\Seat5Incognito;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Services\AbstractSeatPlugin;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpContracts;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembers;
use WokeUpChoseViolence\Seat5Incognito\Commands\Corp;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembersLocations;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembersOnline;
use WokeUpChoseViolence\Seat5Incognito\Observers\CharacterNotificationObserver;
use WokeUpChoseViolence\Seat5Incognito\Observers\ContractDetailObserver;

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
        $this->add_observers();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/package.character.menu.php', 'package.character.menu');
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

    private function add_observers()
    {
        CharacterNotification::flushEventListeners();
        CharacterNotification::observe(CharacterNotificationObserver::class);

        ContractDetail::observe(ContractDetailObserver::class);
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
}
