<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

 namespace WokeUpChoseViolence\Seat5Incognito\Commands\Update;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Database\Eloquent\Builder;
use Seat\Eveapi\Jobs\Assets\Character\Assets;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CorpMemberAssets
 */
class CorpMemberAssets extends Command implements Isolatable
{
    /**
     * @var string
     */
    protected $signature = 'bomb:corp-member-assets {corporationId}';

    /**
     * @var string
     */
    protected $description = "Schedule update jobs for a corporation's members' assets";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $corporation_id = $this->argument('corporationId');

        // in case requested contract are unknown, enqueue list jobs which will collect all contracts
        if (! $corporation_id) {
            $this->error('CorporationID argument is missing.');
            return 1;
        }

        // collect contract from corporation related to asked contracts
        $this->enqueueDetailedCorporationMemberAssetsJobs($corporation_id);
    }

    /**
     * Enqueue relevant detail jobs for requested corporation contracts.
     *
     * @param  string  $corporation_id
     */
    private function enqueueDetailedCorporationMemberAssetsJobs(string $corporation_id)
    {

        $users = User::whereHas('refresh_tokens.affiliation.corporation', function (Builder $query) use ($corporation_id) {
            $query->where('refresh_tokens.affiliation.corporation.corporation_id', $corporation_id);
        })->get();

        $users->each(function (User $user) {
            $this->warn('Pulling assets for ' . $user->main_character()->name);
            $user->all_characters()->each(function (CharacterInfo $character) {
                Assets::dispatch($character->refresh_token());
            });
        });
    }
}