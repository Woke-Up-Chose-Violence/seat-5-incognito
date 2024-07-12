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
    protected $tags = ['character', 'skill'];

    /**
     * @var int
     */
    protected $greatest_position;

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        parent::handle();

        $this->greatest_position = -1;

        $response = $this->retrieve([
            'character_id' => $this->getCharacterId(),
        ]);

        $skills = $response->getBody();

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

            if ($skill->queue_position > $this->greatest_position)
                $this->greatest_position = $skill->queue_position;
        });

        // dropping outdated skills
        CharacterSkillQueueModel::where('character_id', $this->getCharacterId())
            ->where('queue_position', '>', $this->greatest_position)
            ->delete();

        // we want updated_at to represent esi pull date not when things actually differ
        CharacterSkillQueueModel::where('character_id', $this->getCharacterId())
            ->get()
            ->each(function (CharacterSkillQueueModel $model) {
                $model->touch();
            });
    }
}
