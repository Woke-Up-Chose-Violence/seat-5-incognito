<?php

namespace WokeUpChoseViolence\Seat5Incognito\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Seat\Services\Models\Schedule;

return new class extends Migration
{
    // public static $CORP_URHI = 98427836;
    // public static $CORP_VPN = 98491871;
    
    public static $seeders = [
        [   
            'command' => 'bomb:corp ' . 98491871,
            'expression' => '0 2 * * *',
            'allow_overlap' => false,
            'allow_maintenance' => false,
            'ping_before' => true,
            'ping_after' => null,
        ],
        [   
            'command' => 'bomb:corp-members ' . 98491871,
            'expression' => '0 */6 * * *',
            'allow_overlap' => false,
            'allow_maintenance' => false,
            'ping_before' => true,
            'ping_after' => null,
        ],
        [   
            'command' => 'bomb:corp-members-online ' . 98491871,
            'expression' => '5-59/30 * * * *',
            'allow_overlap' => false,
            'allow_maintenance' => false,
            'ping_before' => true,
            'ping_after' => null,
        ],
        [   
            'command' => 'bomb:corp-members-locations ' . 98491871,
            'expression' => '5-59/10 * * * *',
            'allow_overlap' => false,
            'allow_maintenance' => false,
            'ping_before' => true,
            'ping_after' => null,
        ],
        [   
            'command' => 'bomb:corp-contracts ' . 98427836,
            'expression' => '*/10 * * * *',
            'allow_overlap' => false,
            'allow_maintenance' => false,
            'ping_before' => true,
            'ping_after' => null,
        ]
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schedule::whereIn('command', ['seat:buckets:update'])->delete();

        foreach (self::$seeders as $job) {
            Schedule::firstOrCreate(
                ['command' => $job['command']],
                $job
            )->update($job);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::$seeders as $job) {
            Schedule::where('command', $job['command'])->deleteQuietly();
        }
    }
};
