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

use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Seat\Eveapi\Jobs\Contracts\Corporation\Bids as CorporationBids;
use Seat\Eveapi\Jobs\Contracts\Corporation\Items as CorporationItems;
use Seat\Eveapi\Models\Contracts\CorporationContract;
use Seat\Eveapi\Models\RefreshToken;

/**
 * Class CorpContracts
 */
class CorpContracts extends Command implements Isolatable
{
    /**
     * @var string
     */
    protected $signature = 'bomb:corp-contracts {corporationId}';

    /**
     * @var string
     */
    protected $description = "Schedule update jobs for a corporation's contracts";

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
        CorporationContract::where('corporation_id', $corporation_id)
            ->whereHas('detail', function (QueryBuilder $query) {
                $query
                    ->where('status', '<>', 'deleted')
                    ->andWhere('date_issued', '>=', Carbon::now()->subDays(7));
            })
            ->chunk(200, function ($contracts) {
                $token = null;

                foreach ($contracts as $contract) {

                    // attempt to locate a token for the required corporation
                    if (! $token || $token->character->affiliation->corporation_id != $contract->corporation_id) {
                        $token = RefreshToken::whereHas('character', function ($query) use ($contract) {
                            $query->whereHas('affiliation', function ($query) use ($contract) {
                                $query->where('corporation_id', $contract->corporation_id);
                            });
                            $query->whereHas('corporation_roles', function ($query) {
                                $query->where('scope', 'roles');
                                $query->where('role', 'Director');
                            });
                        })->first();
                    }

                    if (is_null($token)) {
                        $this->warn(sprintf('No valid token for Corporation %d - requested by Contract %d',
                            $contract->corporation_id, $contract->contract_id));
                        continue;
                    }

                    // for each non deleted contract, enqueue relevant detailled jobs
                    if ($contract->detail->type == 'auction')
                        CorporationBids::dispatch($contract->corporation_id, $token, $contract->contract_id);

                    if ($contract->detail->type != 'courier' && $contract->detail->volume > 0)
                        CorporationItems::dispatch($contract->corporation_id, $token, $contract->contract_id);
                }
            });
    }
}