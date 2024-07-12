<?php

namespace WokeUpChoseViolence\Seat5Incognito\Jobs;

use Seat\Eveapi\Jobs\AbstractAuthCharacterJob;
use Seat\Eveapi\Mapping\Characters\SkillQueueMapping;
use Seat\Eveapi\Models\Skills\CharacterSkillQueue as CharacterSkillQueueModel;

class CharacterSkillQueue extends AbstractAuthCharacterJob
{
    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/characters/{character_id}/skillqueue/';

    /**
     * @var int
     */
    protected $version = 'v2';

    /**
     * @var string
     */
    protected $scope = 'esi-skills.read_skillqueue.v1';

    /**
     * @var array
     */
    protected $tags = ['character', 'skill', 'skillqueue'];

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        parent::handle();

        $response = $this->retrieve([
            'character_id' => $this->getCharacterId(),
        ]);

        $skills = $response->getBody();

        CharacterSkillQueueModel::where('character_id', $this->getCharacterId())->delete();

        collect($skills)->each(function ($skill) {

            $model = CharacterSkillQueueModel::firstOrNew([
                'character_id' => $this->getCharacterId(),
                'queue_position' => $skill->queue_position,
            ]);

            SkillQueueMapping::make($model, $skill, [
                'character_id' => function () {
                    return $this->getCharacterId();
                },
            ])->save();
        });
    }
}
