<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Seat\Eveapi\Models\Location\CharacterOnline;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\User;

/**
 * Class BaseCorpCommand
 */
abstract class BaseCorpCommand extends Command implements Isolatable
{
    public function handle()
    {
        $corporation_id = $this->argument('corporationId');

        if (! $corporation_id) {
            $this->error('CorporationID argument is missing.');
            return self::FAILURE;
        }

        return $this->handleCommand($corporation_id);
    }

    protected function getCorporationDirectorToken($corporation_id): RefreshToken {
        return RefreshToken::whereHas('character', function ($query) use ($corporation_id) {
            $query->whereHas('affiliation', function ($query) use ($corporation_id) {
                $query->where('corporation_id', $corporation_id);
            });
            $query->whereHas('corporation_roles', function ($query) {
                $query->where('scope', 'roles');
                $query->where('role', 'Director');
            });
        })->first();
    }

    /**
     * @return Collection|User[]
     */
    protected final function getActiveCorporationUsers($corporation_id) {
        return User::whereHas('refresh_tokens.affiliation.corporation', function (Builder $query) use ($corporation_id) {
            $query->where('corporation_id', $corporation_id);
        })->whereHas('characters.online', function (Builder|CharacterOnline $query) {
            $query->where('last_login', '>', Carbon::now()->subDays(7));
        })->get();
    }

    /**
     * @return Collection|User[]
     */
    protected final function getActiveAllianceUsers($alliance_id) {
        return User::whereHas('refresh_tokens.affiliation.alliance', function (Builder $query) use ($alliance_id) {
            $query->where('alliance_id', $alliance_id);
        })->whereHas('characters.online', function (Builder|CharacterOnline $query) {
            $query->where('last_login', '>', Carbon::now()->subDays(7));
        })->get();
    }


    abstract protected function handleCommand($corporation_id): int;
}