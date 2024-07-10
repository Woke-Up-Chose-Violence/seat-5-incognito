<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use Seat\Eveapi\Bus\Character;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CorpMembers
 */
class CorpMembers extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-members {corporationId}';

    protected $description = "Schedule update jobs for a corporation's members";

    protected function handleCommand($corporation_id): int
    {
        $this->getActiveCorporationUsers($corporation_id)
            ->each(function (User $user) {
                $user->all_characters()->each(function (CharacterInfo $character) {
                    (new Character($character->character_id, $character->refresh_token))->fire();
                    $this->call('esi:update:notifications', ['character_id' => $character->character_id]);
                });
            });

        return self::SUCCESS;
    }
}