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

namespace tehraven\Seat\CharacterLocationMap\Http\Controllers;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\Region;
use Seat\Web\Http\Controllers\Controller;


/**
 * Class CharacterMapController.
 *
 * @package tehraven\Seat\CharacterLocationMap\Http\Controllers
 */
class CharacterMapController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getMap()
    {
        $characters = $this->getCharacters();

        return view('characterlocationmap::map', compact('characters'));
    }

    public function getRegionMap(int $region_id)
    {
        $region = Region::find($region_id);
        $characters = $this->getCharacters($region_id);

        return view('characterlocationmap::map', compact('characters', 'region'));
    }

    /**
     * @return array List of Characters Grouped by Location Type Keys
     */
    private function getCharacters(int $region_id = null)
    {
        $user = auth()->user();
        $characters = null;

        if ($user->can('character.location')) {
            $characters = CharacterInfo::with('location');
        } else {
            $characters = $user->characters();
        }

        if (!is_null($region_id)) {
            $characters->load(['location.solar_system.region' => function ($query) use ($region_id) {
                $query->where('region_id', $region_id);
            }]);
        }

        print_r($characters->toSql());

        return $characters->get()->sortBy(function ($character) {
            if (!$character->location) {
                return 'Unknown';
            } elseif ($character->location->solar_system) {
                return $character->location->solar_system->name;
            }
            return 'Other';
        });
    }
}
