<?php

namespace WokeUpChoseViolence\Seat5Incognito\Observers;

use Seat\Eveapi\Bus\Character;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        $token = RefreshToken::find($user->main_character_id);

        if ($token) {
            (new Character($token->character_id, $token))->fire();
        }
    }
}
