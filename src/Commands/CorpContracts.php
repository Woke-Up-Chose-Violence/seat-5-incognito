<?php

namespace WokeUpChoseViolence\Seat5Incognito\Commands;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Seat\Eveapi\Jobs\Contracts\Corporation\Bids as CorporationBids;
use Seat\Eveapi\Jobs\Contracts\Corporation\Items as CorporationItems;
use Seat\Eveapi\Models\Contracts\CorporationContract;
use WokeUpChoseViolence\Seat5Incognito\Commands\BaseCorpCommand;
use WokeUpChoseViolence\Seat5Incognito\Jobs\CorporationContracts;

/**
 * Class CorpContracts
 */
class CorpContracts extends BaseCorpCommand
{
    protected $signature = 'bomb:corp-contracts {corporationId}';

    protected $description = "Schedule update jobs for a corporation's contracts";

    protected function handleCommand($corporation_id): int
    {
        $corporationRefreshToken = $this->getCorporationDirectorToken($corporation_id);

        if (is_null($corporationRefreshToken)) {
            $this->warn(sprintf('No valid token for Corporation %d'));
            return self::FAILURE;
        }

        CorporationContracts::dispatch($corporation_id, $corporationRefreshToken);

        CorporationContract::where('corporation_id', $corporation_id)
            ->whereHas('detail', function (Builder $query) {
                $query
                    ->where('status', '<>', 'deleted')
                    ->where('date_issued', '>=', Carbon::now()->subDays(7));
            })
            ->chunk(200, function ($contracts) use ($corporationRefreshToken) {
                foreach ($contracts as $contract) {

                    // for each non deleted contract, enqueue relevant detailled jobs
                    if ($contract->detail->type == 'auction')
                        CorporationBids::dispatch($contract->corporation_id, $corporationRefreshToken, $contract->contract_id);

                    if ($contract->detail->type != 'courier' && $contract->detail->volume > 0)
                        CorporationItems::dispatch($contract->corporation_id, $corporationRefreshToken, $contract->contract_id);
                }
            });

        return self::SUCCESS;
    }
}