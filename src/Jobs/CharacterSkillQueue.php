<?php

namespace WokeUpChoseViolence\Seat5Incognito\Jobs;

use Carbon\Carbon;
use Seat\Eveapi\Jobs\Skills\Character\Queue;
use Seat\Eveapi\Models\Skills\CharacterSkillQueue as CharacterSkillQueueModel;

class CharacterSkillQueue extends Queue
{
    public function handle()
    { 
        CharacterSkillQueueModel::where('character_id', $this->getCharacterId())
            ->where('updated_at', '<', Carbon::now()->subDays(7))
            ->delete();

        parent::handle();
    }
}
