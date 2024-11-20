<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use \Illuminate\Support\Facades\Bus;
use Seat\Eveapi\Jobs\Character\Notifications;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class notifications
 */
class CorpMembersNotifications extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-members-noitifications {corporationId}';

    protected $description = "Schedule noitifications jobs for a corporation's members";

    protected function handleCommand($corporation_id): int
    {
        $this->getActiveCorporationUsers($corporation_id)
            ->each(function (User $user) {
                $user->all_characters()->each(function (CharacterInfo $character) {
                    Bus::batch([
                        new Notifications($character->refresh_token),
                    ])->onQueue('characters')->name($character->name)->dispatch();
                });
            });

        return self::SUCCESS;
    }
}