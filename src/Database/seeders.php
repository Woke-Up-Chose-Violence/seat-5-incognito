<?php
namespace WokeUpChoseViolence\Seat5Incognito\Database;

use App\Console\Kernel;
use Seat\Services\Seeding\AbstractScheduleSeeder;

class CorpJobSeeders extends AbstractScheduleSeeder {
    static $CORP_URHI = 98427836;
    static $CORP_VPN = 98491871;

    public function getSchedules(): array {
        return [
            [   
                'command' => 'bomb:corp '.self::$CORP_VPN,
                'expression' => '0 2 * * *',
                'allow_overlap' => false,
                'allow_maintenance' => false,
                'ping_before' => true,
                'ping_after' => null,
            ],
            [   
                'command' => 'bomb:corp-members '.self::$CORP_VPN,
                'expression' => '0 */6 * * *',
                'allow_overlap' => false,
                'allow_maintenance' => false,
                'ping_before' => true,
                'ping_after' => null,
            ],
            [   
                'command' => 'bomb:corp-members-locations '.self::$CORP_VPN,
                'expression' => '*/15 * * * *',
                'allow_overlap' => false,
                'allow_maintenance' => false,
                'ping_before' => true,
                'ping_after' => null,
            ],
            [   
                'command' => 'bomb:corp-contracts '.self::$CORP_URHI,
                'expression' => '*/10 * * * *',
                'allow_overlap' => false,
                'allow_maintenance' => false,
                'ping_before' => true,
                'ping_after' => null,
            ]
        ];
    }

    public function getDeprecatedSchedules(): array {
        return ['seat:buckets:update'];
    }
}