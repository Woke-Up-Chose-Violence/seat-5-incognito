<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use WokeUpChoseViolence\Seat5Incognito\Commands\BaseCorpCommand;

/**
 * Class Corp
 */
class Corp extends BaseCorpCommand
{
    protected $signature = 'bomb:corp {corporationId}';

    protected $description = "Schedule update jobs for a corporation";


    protected function handleCommand($corporation_id): int
    {
        $corporationRefreshToken = $this->getCorporationDirectorToken($corporation_id);

        if (is_null($corporationRefreshToken)) {
            $this->warn(sprintf('No valid token for Corporation %d'));
            return self::FAILURE;
        }

        $this->call('esi:update:corporations', ['character_id' => $corporationRefreshToken->character_id]);

        return self::SUCCESS;
    }
}