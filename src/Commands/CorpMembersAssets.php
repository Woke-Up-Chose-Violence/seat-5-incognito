<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use \Illuminate\Support\Facades\Bus;
use Seat\Eveapi\Jobs\Assets\Character\Assets;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CorpMembers
 */
class CorpMembersAssets extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-members-assets {corporationId}';

    protected $description = "Schedule assets jobs for a corporation's members";

    protected function handleCommand($corporation_id): int
    {
        $this->getActiveCorporationUsers($corporation_id)
            ->each(function (User $user) {
                $user->all_characters()->each(function (CharacterInfo $character) {
                    Bus::batch([
                        new Assets($character->refresh_token),
                    ])->onQueue('characters')->name($character->name)->dispatch();
                });
            });

        return self::SUCCESS;
    }
}