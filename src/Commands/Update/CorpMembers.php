<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

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
                    $this->call('esi:update:characters', ['character_id' => $character->character_id]);
                    $this->call('esi:update:notifications', ['character_id' => $character->character_id]);
                });
            });

        return self::SUCCESS;
    }
}