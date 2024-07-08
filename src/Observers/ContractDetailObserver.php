<?php

namespace WokeUpChoseViolence\Seat5Incognito\Observers;

use Illuminate\Support\Facades\Notification;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Notifications\Models\Integration;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use WokeUpChoseViolence\Seat5Incognito\Classes\VPNClassVars;

class ContractDetailObserver extends VPNClassVars
{
    protected function weCareAboutThis(ContractDetail $contract)
    {
        return $contract->assignee->category === 'corporation' && $contract->assignee->entity_id === $this->URHI_Corp;
    }
    public function created(ContractDetail $contract)
    {
        if ($this->weCareAboutThis($contract))
        {
            $logiChannel = Integration::where('name', '=', 'Logistics Channel')->first();
            if ($logiChannel)
            {
                $setting = (array) $logiChannel->settings;
                $key = array_key_first($setting);
                $route = $setting[$key];

                Notification::route($logiChannel->type, $route)
                    ->notify((new DiscordMessage)
                        ->from('Urhi Inc', 'https://images.evetech.net/corporations/98427836/logo?size=64')
                        ->info()
                        ->content('A new contract was created for Urhi')
                        ->embed(function (DiscordEmbed $embed) use ($contract) {
                            $embed
                                ->author($contract->issuer->name, 'https://images.evetech.net/characters/' . $contract->issuer->entity_id . '/portrait?size=64')
                                ->title($contract->start_location->name . ' --> ' . $contract->end_location->name)
                                ->fields([
                                    'Volume' => $contract->volume,
                                    'Reward' => $contract->reward
                                ]);
                        })
                    );
            }
        }
    }
}
