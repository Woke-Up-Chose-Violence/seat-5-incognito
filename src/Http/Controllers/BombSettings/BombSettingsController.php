<?php

namespace WokeUpChoseViolence\Seat5Incognito\Http\Controllers\BombSettings;

use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use WokeUpChoseViolence\Seat5Incognito\Models\BombSettings;

class BombSettingsController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getSettings()
    {
        $settings = $this->getUserBombSettings();

        return view('woke-up-chose-violence::settings.home', $settings);
    }

    public function saveSettings(Request $request) {
        $request->validate([
            'skill_queue_warnings'  => 'required|boolean',
            'industry_warnings'     => 'required|boolean',
            'fc_fleet_bot'          => 'required|boolean',
        ]);

        $settings = $this->getUserBombSettings();

        $settings->skill_queue_warnings = $request->input('skill_queue_warnings') == '1';
        $settings->industry_warnings = $request->input('industry_warnings') == '1';
        $settings->fc_fleet_bot = $request->input('fc_fleet_bot') == '1';

        $settings->update();

        return redirect()->route('woke-up-chose-violence.settings.home')->with('success', 'Settings Updated');
    }

    private function getUserBombSettings(): BombSettings
    {
        /**
         * @var User $user
         */
        $user = auth()->user();
        return BombSettings::firstOrCreate([
            'user_id' => $user->id
        ], [
            'user_id' => $user->id
        ]);
    }
}
