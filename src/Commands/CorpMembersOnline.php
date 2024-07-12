<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use \Illuminate\Support\Facades\Bus;
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
                    Bus::batch([
                        new Online($character->refresh_token),
                        new Location($character->refresh_token),
                        new Ship($character->refresh_token),
                    ])->onQueue('characters')->name($character->name)->allowFailures()->dispatch();
                });
            });

        return self::SUCCESS;
    }
}