<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use \Illuminate\Support\Facades\Bus;
use Seat\Eveapi\Jobs\Skills\Character\Attributes;
use Seat\Eveapi\Jobs\Skills\Character\Skills;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;
use WokeUpChoseViolence\Seat5Incognito\Jobs\CharacterSkillQueue;

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
                    Bus::batch([
                        new CharacterSkillQueue($character->refresh_token),
                        new Attributes($character->refresh_token),
                        new Skills($character->refresh_token),
                    ])->onQueue('characters')->name($character->name)->allowFailures()->dispatch();
                });
            });

        return self::SUCCESS;
    }
}
