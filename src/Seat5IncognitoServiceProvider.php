<?php

namespace WokeUpChoseViolence\Seat5Incognito;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Services\AbstractSeatPlugin;
use Seat\Web\Models\User;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpContracts;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembers;
use WokeUpChoseViolence\Seat5Incognito\Commands\Corp;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembersAssets;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembersLocations;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembersOnline;
use WokeUpChoseViolence\Seat5Incognito\Commands\CorpMembersSkills;
use WokeUpChoseViolence\Seat5Incognito\Observers\CharacterNotificationObserver;
use WokeUpChoseViolence\Seat5Incognito\Observers\ContractDetailObserver;
use WokeUpChoseViolence\Seat5Incognito\Observers\UserObserver;

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
        $this->mergeConfigFrom(__DIR__ . '/Config/package.sidebar.php', 'package.sidebar');        
        $this->registerPermissions(__DIR__ . '/Config/woke-up-chose-violence.permissions.php', 'woke-up-chose-violence');
    }

    /**
     * Include routes.
     */
    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    /**
     * Import translations.
     */
    private function add_translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'woke-up-chose-violence');
    }

    /**
     * Import views.
     */
    private function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'woke-up-chose-violence');
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

        User::observe(UserObserver::class);
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
            CorpMembersSkills::class,
            CorpMembersAssets::class,
        ]);
    }

    public function getName(): string
    {
        return 'SeAT for Woke Up Chose Violence (alliance)';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/Woke-Up-Chose-Violence/seat-5-incognito';
    }

    public function getPackagistPackageName(): string
    {
        return 'seat-5-incognito';
    }

    public function getPackagistVendorName(): string
    {
        return 'woke-up-chose-violence';
    }
}
