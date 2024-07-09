<?php
/*
This file is part of SeAT

Copyright (C) 2015 to 2020  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace WokeUpChoseViolence\Seat5Incognito\Http\Controllers;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\Region;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Web\Http\Controllers\Controller;


/**
 * Class CharacterMapController.
 *
 * @package WokeUpChoseViolence\Seat5Incognito\Http\Controllers
 */
class CharacterMapController extends Controller
{
    private function getStatic(int $region_id = null, int $system_id = null)
    {
        $allRegions = Region::all()->sortBy('name');
        $region = null;
        $system = null;
        $characters = $this->getCharacters($region_id, $system_id);
        $svg = null;
        if ($system_id) {
            $system = SolarSystem::find($system_id);
            $region = Region::find($system->region_id);
        } elseif ($region_id) {
            $region = Region::find($region_id);
            $svg = file_get_contents('https://raw.githubusercontent.com/Slazanger/SMT/master/EVEData/data/SourceMaps/dotlan/' . join('_', explode(' ', $region->name)) . '.svg');
        }
        return [
            'allRegions' => $allRegions,
            'characters' => $characters,
            'region' => $region,
            'system' => $system,
            'svg' => $svg
        ];
    }
    /**
     * @return \Illuminate\View\View
     */
    public function getMap()
    {
        $static = $this->getStatic();

        return view('woke-up-chose-violence::map', $static);
    }

    public function getRegionMap(int $region_id)
    {
        $static = $this->getStatic($region_id);

        return view('woke-up-chose-violence::region', $static);
    }

    public function getSystemMap(int $system_id)
    {
        $static = $this->getStatic(null, $system_id);

        return view('woke-up-chose-violence::system', $static);
    }


    /**
     * @return array List of Characters Grouped by Location Type Keys
     */
    private function getCharacters(int $region_id = null, int $system_id = null): array
    {
        $user = auth()->user();
        $characters = CharacterInfo::with('location', 'location.solar_system', 'location.structure', 'location.station', 'location.solar_system.region', 'online');

        if ($system_id) {
            $characters = $characters->whereHas('location.solar_system', function ($system) use ($system_id) {
                if ($system_id) {
                    $system->where('system_id', $system_id);
                }
            });
        } elseif ($region_id) {
            $characters = $characters->whereHas('location.solar_system.region', function ($region) use ($region_id) {
                if ($region_id) {
                    $region->where('region_id', $region_id);
                }
            });
        }

        if (!$user->can('woke-up-chose-violence.character_map')) {
            $characters = $characters->whereIn('character_id', $user->all_characters);
        }

        return $characters->get()->sortBy(function (CharacterInfo $character) {
            if ($character->location && $character->location->solar_system) {
                return $character->location->solar_system->name;
            }
            return $character->name;
        })->all();
    }
}
