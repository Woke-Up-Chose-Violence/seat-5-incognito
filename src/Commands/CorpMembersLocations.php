<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use Seat\Eveapi\Jobs\Location\Character\Location;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CorpMembersLocations
 */
class CorpMembersLocations extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-members-locations {corporationId}';

    protected $description = "Schedule update jobs for a corporation's members' locations";

    protected function handleCommand($corporation_id): int
    {
        $this->getActiveCorporationUsers($corporation_id)
            ->each(function (User $user) {
                $user->all_characters()->each(function (CharacterInfo $character) {
                    Location::dispatch($character->refresh_token);
                });
            });

        return self::SUCCESS;
    }
}