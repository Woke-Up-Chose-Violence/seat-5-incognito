<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use Seat\Eveapi\Jobs\Skills\Character\Attributes;
use Seat\Eveapi\Jobs\Skills\Character\Queue;
use Seat\Eveapi\Jobs\Skills\Character\Skills;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CorpMemberAssets
 */
class CorpMembersSkills extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-members-skills {corporationId}';

    protected $description = "Schedule update jobs for a corporation's members' skills";

    protected function handleCommand($corporation_id): int
    {
        $this->getActiveCorporationUsers($corporation_id)
            ->each(function (User $user) {
                $user->all_characters()->each(function (CharacterInfo $character) {
                    Attributes::dispatch($character->refresh_token);
                    Queue::dispatch($character->refresh_token);
                    Skills::dispatch($character->refresh_token);
                });
            });

        return self::SUCCESS;
    }
}