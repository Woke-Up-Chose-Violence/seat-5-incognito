<?php

namespace WokeUpChoseViolence\Seat5Incognito\Jobs;

use Seat\Eveapi\Jobs\AbstractAuthCorporationJob;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Eveapi\Models\Contracts\CorporationContract;

/**
 * Class Contracts.
 *
 * @package Seat\Eveapi\Jobs\Contracts\Corporation
 */
class CorporationContracts extends AbstractAuthCorporationJob
{
    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/corporations/{corporation_id}/contracts/';

    /**
     * @var string
     */
    protected $version = 'v1';

    /**
     * @var string
     */
    protected $scope = 'esi-contracts.read_corporation_contracts.v1';

    /**
     * @var array
     */
    protected $tags = ['corporation', 'contract'];

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function handle()
    {
        while (true) {

            $response = $this->retrieve([
                'corporation_id' => $this->getCorporationId(),
            ]);

            $contracts = $response->getBody();

            collect($contracts)->each(function ($contract) {
                // Update or create the contract details.
                $model = ContractDetail::firstOrNew([
                    'contract_id' => $contract->contract_id,
                ]);

                $model->fromEsi($contract);
                if($model->save())
                {
                    $model->updateTimestamps();
                }

                // Ensure the character is associated to this contract
                CorporationContract::firstOrCreate([
                    'corporation_id' => $this->getCorporationId(),
                    'contract_id' => $contract->contract_id,
                ]);
            });

            if (! $this->nextPage($response->getPagesCount())) {
                break;
            }
        }
    }
}
