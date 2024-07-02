<?php

namespace WokeUpChoseViolence\Seat5Incognito\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Seat\Services\Models\Schedule;

return new class extends Migration
{
    static $CORP_URHI = 98427836;
    static $CORP_VPN = 98491871;
    static $seeders = [
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
            'command' => 'bomb:corp-members-online '.self::$CORP_VPN,
            'expression' => '5-59/30 * * * *',
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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schedule::whereIn('command', ['seat:buckets:update'])->delete();

        foreach (self::$seeders as $job) {
            $existing = Schedule::where('command', $job['command'])
                          ->first();

            if ($existing) {
                $existing->update([
                    'expression' => $job['expression'],
                ]);
            }

            if (! $existing) {
                DB::table('schedules')->insert($job);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::$seeders as $job) {
            $existing = Schedule::where('command', $job['command'])
                          ->first();

            if ($existing) {
                $existing->delete();
            }
        }
    }
};
