<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use Seat\Eveapi\Jobs\Location\Character\Location;
use Seat\Eveapi\Jobs\Location\Character\Online;
use Seat\Eveapi\Jobs\Location\Character\Ship;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CorpMembersOnline
 */
class CorpMembersOnline extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-members-online {corporationId}';

    protected $description = "Schedule update jobs for a corporation's members' online info";

    protected function handleCommand($corporation_id): int
    {
        $this->getActiveCorporationUsers($corporation_id)
            ->each(function (User $user) {
                $user->all_characters()->each(function (CharacterInfo $character) {
                    Online::dispatch($character->refresh_token);
                    Location::dispatch($character->refresh_token);
                    Ship::dispatch($character->refresh_token);
                });
            });

        return self::SUCCESS;
    }
}